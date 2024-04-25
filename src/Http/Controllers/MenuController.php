<?php

namespace Tec\Menu\Http\Controllers;

use Tec\Base\Events\BeforeEditContentEvent;
use Tec\Base\Events\CreatedContentEvent;
use Tec\Base\Events\DeletedContentEvent;
use Tec\Base\Events\UpdatedContentEvent;
use Tec\Base\Http\Actions\DeleteResourceAction;
use Tec\Base\Supports\Breadcrumb;
use Tec\Base\Facades\PageTitle;
use Tec\Base\Forms\FormBuilder;
use Tec\Base\Http\Controllers\BaseController;
use Tec\Base\Http\Responses\BaseHttpResponse;
use Tec\Menu\Facades\Menu;
use Tec\Menu\Forms\MenuForm;
use Tec\Menu\Forms\MenuNodeForm;
use Tec\Menu\Http\Requests\MenuNodeRequest;
use Tec\Menu\Http\Requests\MenuRequest;
use Tec\Menu\Models\Menu as MenuModel;
use Tec\Menu\Models\MenuLocation;
use Tec\Menu\Models\MenuNode;
use Tec\Menu\Repositories\Eloquent\MenuRepository;
use Tec\Menu\Tables\MenuTable;
use Tec\Support\Services\Cache\Cache;
use Exception;
use Illuminate\Cache\CacheManager;
use Illuminate\Http\Request;
use stdClass;
use Tec\Menu\Events\RenderingMenuOptions;

class MenuController extends BaseController
{
    protected Cache $cache;

    public function __construct(CacheManager $cache)
    {
        $this->cache = new Cache($cache, MenuRepository::class);
    }

	 protected function breadcrumb(): Breadcrumb
	 {
			return parent::breadcrumb()
				 ->add(trans('packages/theme::theme.appearance'))
				 ->add(trans('packages/menu::menu.name'), route('menus.index'));
	 }

    public function index(MenuTable $table)
    {
        PageTitle::setTitle(trans('packages/menu::menu.name'));

        return $table->renderTable();
    }

    public function create()
    {
        RenderingMenuOptions::dispatch();
        PageTitle::setTitle(trans('packages/menu::menu.create'));

        return MenuForm::create()->renderForm();
    }

    public function store(MenuRequest $request)
    {
			 $form = MenuForm::create();
			 $form
					->saving(function (MenuForm $form) use ($request) {
						 $form
								->getModel()
								->fill($form->getRequest()->input())
								->save();
						 $this->cache->flush();
						 $this->saveMenuLocations($form->getModel(), $request);
					});
		 	 return $this
					->httpResponse()
					->setPreviousRoute('menus.index')
					->setNextRoute('menus.edit', $form->getModel()->getKey())
					->withCreatedSuccessMessage();
    }

    protected function saveMenuLocations(MenuModel $menu, Request $request): bool
    {
			 $locations = $request->input('locations', []);

			 MenuLocation::query()
					->where('menu_id', $menu->getKey())
					->whereNotIn('location', $locations)
					->each(fn (MenuLocation $location) => $location->delete());

			 foreach ($locations as $location) {
					$menuLocation = MenuLocation::query()->firstOrCreate([
																																	'menu_id' => $menu->getKey(),
																																	'location' => $location,
																															 ]);

					event(new CreatedContentEvent(MENU_LOCATION_MODULE_SCREEN_NAME, $request, $menuLocation));
			 }

			 return true;
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        RenderingMenuOptions::dispatch();
        $oldInputs = old();
        if ($oldInputs && $id == 0) {
            $oldObject = new stdClass();
            foreach ($oldInputs as $key => $row) {
                $oldObject->$key = $row;
            }
            $menu = $oldObject;
        } else {
            $menu = MenuModel::query()->findOrFail($id);
        }

        PageTitle::setTitle(trans('core/base::forms.edit_item', ['name' => $menu->name]));

        /**
         * @var MenuModel $menu
         */
        event(new BeforeEditContentEvent($request, $menu));

        return $formBuilder->create(MenuForm::class, ['model' => $menu])->renderForm();
    }

    public function update(MenuModel $menu, MenuRequest $request, BaseHttpResponse $response)
    {
			 MenuForm::createFromModel($menu)
					->saving(function (MenuForm $form) use ($request) {
						 $form
								->getModel()
								->fill($form->getRequest()->input())
								->save();

						 $this->saveMenuLocations($form->getModel(), $request);
					});

        event(new UpdatedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));

        $deletedNodes = ltrim((string)$request->input('deleted_nodes', ''));
        if ($deletedNodes && $deletedNodes = array_filter(explode(' ', $deletedNodes))) {
            $menu->menuNodes()->whereIn('id', $deletedNodes)->delete();
        }

        $menuNodes = Menu::recursiveSaveMenu((array)json_decode($request->input('menu_nodes'), true), $menu->getKey(), 0);

        $request->merge(['menu_nodes', json_encode($menuNodes)]);

        $this->cache->flush();

			 return $this
					->httpResponse()
					->setPreviousRoute('menus.index')
					->withUpdatedSuccessMessage();
    }

    public function destroy(MenuModel $menu, Request $request, BaseHttpResponse $response)
    {
        try {
					 event(new DeletedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));
					 return DeleteResourceAction::make($menu);

        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function getNode(MenuNodeRequest $request, BaseHttpResponse $response)
    {
			 $form = MenuNodeForm::create();

			 $form->saving(function (MenuNodeForm $form) use ($request) {
					$row = $form->getModel();
					$row->fill($data = $request->input('data', []));
					$row = Menu::getReferenceMenuNode($data, $row);
					$row->save();
			 });

			 return $this
					->httpResponse()
					->setData([
											 'html' => view('packages/menu::partials.node', ['row' => $form->getModel()])->render(),
										])
					->withCreatedSuccessMessage();
    }
}
