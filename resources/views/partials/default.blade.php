<ul {!! $options !!}>
    @foreach ($menu_nodes as $key => $row)
        <li
            @if ($row->css_class || $row->active) class="@if ($row->css_class) {{ $row->css_class }} @endif
            @if ($row->active) current @endif"
            @endif>
            <a
                href="{{ url($row->url) }}"
                title="{{ $row->title }}"
                @if ($row->target !== '_self') target="{{ $row->target }}" @endif
            >
                @if ($row->icon_font) <i class="{{ trim($row->icon_font) }}"></i> @endif
                <span>{!! BaseHelper::clean($row->title) !!}</span>
            </a>
            @if ($row->has_child)
                {!! Tec\Menu\Facades\Menu::generateMenu([
                    'menu' => $menu,
                    'menu_nodes' => $row->child,
                ]) !!}
            @endif
        </li>
    @endforeach
</ul>
