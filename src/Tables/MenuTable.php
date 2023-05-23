<?php

namespace Tec\Menu\Tables;

use BaseHelper;
use RvMedia;
use Tec\Base\Enums\BaseStatusEnum;
use Tec\Menu\Repositories\Interfaces\MenuInterface;
use Tec\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class MenuTable extends TableAbstract
{
    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * MenuTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param MenuInterface $menuRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, MenuInterface $menuRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $menuRepository;

        if (!Auth::user()->hasAnyPermission(['menus.edit', 'menus.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('image', function ($item) {
                return Html::image(RvMedia::getImageUrl($item->image, 'thumb', false, RvMedia::getDefaultImage()),
                    $item->name, ['width' => 50]);
            })
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('menus.edit')) {
                    return $item->name;
                }

                return Html::link(route('menus.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('template', function ($item) {
                return ucfirst($item->template);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('menus.edit', 'menus.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'image',
                'name',
                'template',
                'created_at',
                'status',
            ]);

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'image'      => [
                'name'  => 'image',
                'title' => trans('core/base::tables.image'),
                'width' => '70px',
            ],
            'name'       => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'template' => [
                'title' => trans('packages/menu::menu.template'),
                'width' => '100px',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        return $this->addCreateButton(route('menus.create'), 'menus.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('menus.deletes'), 'menus.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'customSelect',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
