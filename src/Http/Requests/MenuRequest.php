<?php

namespace Tec\Menu\Http\Requests;

use Tec\Base\Enums\BaseStatusEnum;
use Tec\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class MenuRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:120',
            'deleted_nodes' => 'nullable|string',
            'menu_nodes' => 'nullable|string',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
