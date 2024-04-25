<?php

namespace Tec\Menu\Forms;

use Tec\Base\Enums\BaseStatusEnum;
use Tec\Base\Facades\Assets;
use Tec\Base\Forms\FieldOptions\NameFieldOption;
use Tec\Base\Forms\FieldOptions\StatusFieldOption;
use Tec\Base\Forms\Fields\SelectField;
use Tec\Base\Forms\Fields\TextField;
use Tec\Base\Forms\FormAbstract;
use Tec\Menu\Enums\MenuTemplateEnum;
use Tec\Menu\Http\Requests\MenuRequest;
use Tec\Menu\Models\Menu;

class MenuForm extends FormAbstract
{
    public function buildForm(): void
    {
			 Assets::addStyles('jquery-nestable')
					->addScripts('jquery-nestable')
					->addScriptsDirectly('vendor/core/packages/menu/js/menu.js')
					->addStylesDirectly('vendor/core/packages/menu/css/menu.css');

        $locations = [];

        if ($this->getModel()) {
            $locations = $this->getModel()->locations()->pluck('location')->all();
        }
        $this
					 ->model(Menu::class)
					 ->setFormOption('class', 'form-save-menu')
            ->withCustomFields()
            ->setValidatorClass(MenuRequest::class)
					  ->add('name', TextField::class, NameFieldOption::make()->required()->maxLength(120)->toArray())
            ->add('image', 'mediaImage', [
                'label'      => trans('packages/menu::menu.image'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->add('template', 'customSelect', [
                'label'      => trans('packages/menu::menu.template'),
                'label_attr' => ['class' => 'control-label'],
                'choices'    => array_map(function($e){
                    return ucfirst(implode(' ',explode('_',$e))); },  MenuTemplateEnum::labels()),

            ])
					 ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
					 ->addMetaBoxes([
														 'structure' => [
																'wrap' => false,
																'content' => function () {
																	 return view('packages/menu::menu-structure', [
																			'menu' => $this->getModel(),
																			'locations' => $this->getModel()->getKey() ? $this->getModel()->locations()->pluck('location')->all() : [],
																	 ])->render();
																},
														 ],
													])
            ->setBreakFieldPoint('status');
    }
}
