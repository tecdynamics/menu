<li
    class="dd-item dd3-item @if ($row->reference_id > 0) post-item @endif"
    data-menu-item="{{ json_encode(Arr::except(apply_filters('menu_nodes_item_data', $row)->toArray(), ['created_at', 'updated_at', 'child'])) }}"
>
    <div class="dd-handle dd3-handle"></div>
    <div class="dd3-content">
        <span
            class="text float-start"
            data-update="title"
        >{{ $row->title }}</span>
        <span
            class="text float-end">{{ Arr::last(explode('\\', (string) $row->reference_type)) ?: trans('packages/menu::menu.custom_link') }}</span>
        <a
            class="show-item-details"
            href="#"
            title=""
        >
            <i class="fa fa-angle-down"></i>
        </a>
        <div class="clearfix"></div>
    </div>
    <div class="item-details">
        {!! app(Tec\Base\Forms\FormBuilder::class)->create(Tec\Menu\Forms\MenuNodeForm::class, ['model' => $row])->renderForm([], false, true, false) !!}
        <div class="clearfix"></div>
        <div class="text-end mt-2">
            <a
                class="btn btn-danger btn-remove btn-sm"
                href="#"
            >{{ trans('packages/menu::menu.remove') }}</a>
            <a
                class="btn btn-primary btn-cancel btn-sm"
                href="#"
            >{{ trans('packages/menu::menu.cancel') }}</a>
        </div>
    </div>
    <div class="clearfix"></div>
    @if ($row->has_child)
        {!! Tec\Menu\Facades\Menu::generateMenu([
            'menu' => $menu,
            'menu_nodes' => $row->child,
            'view' => 'packages/menu::partials.menu',
            'theme' => false,
            'active' => false,
        ]) !!}
    @endif
</li>
