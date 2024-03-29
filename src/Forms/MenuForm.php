<?php

namespace Tec\Menu\Forms;

use Tec\Base\Enums\BaseStatusEnum;
use Tec\Base\Facades\Assets;
use Tec\Base\Forms\FormAbstract;
use Tec\Menu\Enums\MenuTemplateEnum;
use Tec\Menu\Http\Requests\MenuRequest;
use Tec\Menu\Models\Menu;

class MenuForm extends FormAbstract
{
    public function buildForm(): void
    {
        Assets::addScriptsDirectly([
            'vendor/core/packages/menu/libraries/jquery-nestable/jquery.nestable.js',
            'vendor/core/packages/menu/js/menu.js',
        ])
            ->addStylesDirectly([
                'vendor/core/packages/menu/libraries/jquery-nestable/jquery.nestable.css',
                'vendor/core/packages/menu/css/menu.css',
            ]);

        $locations = [];

        if ($this->getModel()) {
            $locations = $this->getModel()->locations()->pluck('location')->all();
        }
        $this
            ->setupModel(new Menu())
            ->setFormOption('class', 'form-save-menu')
            ->withCustomFields()
            ->setValidatorClass(MenuRequest::class)
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
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
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->addMetaBoxes([
                'structure' => [
                    'wrap' => false,
                    'content' => view('packages/menu::menu-structure', [
                        'menu' => $this->getModel(),
                        'locations' => $locations,
                    ])->render(),
                ],
            ])
            ->setBreakFieldPoint('status');
    }
}
