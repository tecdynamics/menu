<?php

namespace Tec\Menu\Forms;

use Tec\Base\Forms\FieldOptions\MediaImageFieldOption;
use Tec\Base\Forms\FieldOptions\SelectFieldOption;
use Tec\Base\Forms\FieldOptions\TextFieldOption;
use Tec\Base\Forms\Fields\MediaImageField;
use Tec\Base\Forms\Fields\SelectField;
use Tec\Base\Forms\Fields\TextField;
use Tec\Base\Forms\FormAbstract;
use Tec\Menu\Models\MenuNode;

class MenuNodeForm extends FormAbstract
{
	 public function setup(): void
	 {
			$this->model(MenuNode::class);
			$id = $this->model->id ?? 'new';
			$this
				 ->contentOnly()
				 ->add(
						'menu_id',
						'hidden',
						TextFieldOption::make()
							 ->value($this->request->route('menu'))
							 ->attributes(['class' => 'menu_id'])
							 ->toArray()
				 )
				 ->add(
						'title',
						TextField::class,
						TextFieldOption::make()
							 ->label(trans('packages/menu::menu.title'))
							 ->labelAttributes([
																		'data-update' => 'title',
																		'for' => 'menu-node-title-' . $id,
																 ])
							 ->placeholder(trans('packages/menu::menu.title_placeholder'))
							 ->attributes([
															 'data-old' => $this->model->title,
															 'id' => 'menu-node-title-' . $id,
														])
							 ->toArray()
				 );
			if (! $this->model->reference_id) {
				 $this
						->add(
							 'url',
							 TextField::class,
							 TextFieldOption::make()
									->label(trans('packages/menu::menu.url'))
									->labelAttributes([
																			 'data-update' => 'custom-url',
																			 'for' => 'menu-node-url-' . $id,
																		])
									->placeholder(trans('packages/menu::menu.url_placeholder'))
									->attributes([
																	'data-old' => $this->model->url,
																	'id' => 'menu-node-url-' . $id,
															 ])
									->toArray()
						);
			}
			$this
				 ->add(
						'icon_font',
						TextField::class,
						TextFieldOption::make()
							 ->label(trans('packages/menu::menu.icon'))
							 ->labelAttributes([
																		'data-update' => 'icon',
																		'for' => 'menu-node-icon-font-' . $id,
																 ])
							 ->placeholder(trans('packages/menu::menu.icon_placeholder'))
							 ->attributes([
															 'data-old' => $this->model->icon_font,
															 'id' => 'menu-node-icon-font-' . $id,
														])
							 ->toArray()
				 )
				 ->add('icon', MediaImageField::class,
						 MediaImageFieldOption::make()
						->label('Image')
						 ->labelAttributes([
							 'class' => 'control-label',
							 'data-update' => 'icon',
							 'for' => 'menu-node-icon-font-' . $id,
						])
					  ->attributes([
							 'data-old' => $this->model->icon,
							 'id' => 'menu-node-icon-' . $id,
						])->toArray()
				  )
				 ->add(
						'css_class',
						TextField::class,
						TextFieldOption::make()
							 ->label(trans('packages/menu::menu.css_class'))
							 ->labelAttributes([
																		'data-update' => 'css_class',
																		'for' => 'menu-node-css-class-' . $id,
																 ])
							 ->placeholder(trans('packages/menu::menu.css_class_placeholder'))
							 ->attributes([
															 'data-old' => $this->model->css_class,
															 'id' => 'menu-node-css-class-' . $id,
														])
							 ->toArray()
				 )
				 ->add(
						'target',
						SelectField::class,
						SelectFieldOption::make()
							 ->label(trans('packages/menu::menu.target'))
							 ->labelAttributes([
																		'data-update' => 'target',
																		'for' => 'menu-node-target-' . $id,
																 ])
							 ->choices([
														'_self' => trans('packages/menu::menu.self_open_link'),
														'_blank' => trans('packages/menu::menu.blank_open_link'),
												 ])
							 ->attributes([
															 'data-old' => $this->model->target,
															 'id' => 'menu-node-target-' . $id,
														])
							 ->toArray()
				 );
	 }
}
