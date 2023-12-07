<?php

namespace Tec\Menu\Tables;

use Tec\Base\Enums\BaseStatusEnum;
use Tec\Menu\Models\Menu;
use Tec\Table\Abstracts\TableAbstract;
use Tec\Table\Actions\DeleteAction;
use Tec\Table\Actions\EditAction;
use Tec\Table\BulkActions\DeleteBulkAction;
use Tec\Table\Columns\CreatedAtColumn;
use Tec\Table\Columns\IdColumn;
use Tec\Table\Columns\NameColumn;
use Tec\Table\Columns\StatusColumn;
use Illuminate\Validation\Rule;

class MenuTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Menu::class)
            ->addActions([
                EditAction::make()->route('menus.edit'),
                DeleteAction::make()->route('menus.destroy'),
            ])
            ->queryUsing(fn ($query) => $query->select([
                'id',
                'name',
                'created_at',
                'status',
            ]));
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            NameColumn::make()->route('menus.edit'),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('menus.create'), 'menus.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('menus.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'customSelect',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }
}
