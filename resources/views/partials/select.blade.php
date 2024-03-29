<ul {!! $options !!}>
    @foreach ($items as $key => $row)
        @php $id = 'menu-id-' . strtolower(Str::slug(str_replace('\\', ' ', get_class($model)))) . '-' . $row->id; @endphp
        <li>
            <label
                data-title="{{ $row->name }}"
                data-reference-id="{{ $row->id }}"
                data-reference-type="{{ get_class($model) }}"
                data-menu-id="{{ BaseHelper::stringify(request()->route('menu')) }}"
                for="{{ $id }}"
            >
                {!! Form::checkbox('menu_id', $row->id, null, compact('id')) !!}
                {{ $row->name }}
            </label>

            @if ($row->children)
                {!! Tec\Menu\Facades\Menu::generateSelect([
                    'model' => $model,
                    'items' => $row->children,
                ]) !!}
            @endif
        </li>
    @endforeach
</ul>
