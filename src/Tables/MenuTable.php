<?php

namespace Tec\Menu\Tables;

use Tec\Menu\Models\Menu;
use Tec\Table\Abstracts\TableAbstract;
use Tec\Table\Actions\DeleteAction;
use Tec\Table\Actions\EditAction;
use Tec\Table\BulkActions\DeleteBulkAction;
use Tec\Table\BulkChanges\CreatedAtBulkChange;
use Tec\Table\BulkChanges\NameBulkChange;
use Tec\Table\BulkChanges\StatusBulkChange;
use Tec\Table\Columns\CreatedAtColumn;
use Tec\Table\Columns\IdColumn;
use Tec\Table\Columns\NameColumn;
use Tec\Table\Columns\StatusColumn;
use Illuminate\Validation\Rule;
use Tec\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;
class MenuTable extends TableAbstract
{
	 public function setup(): void
	 {
			$this
				 ->model(Menu::class)
				 ->addColumns([
												 IdColumn::make(),
												 NameColumn::make()->route('menus.edit'),
												 CreatedAtColumn::make(),
												 StatusColumn::make(),
											])
				 ->addHeaderAction(CreateHeaderAction::make()->route('menus.create'))
				 ->addActions([
												 EditAction::make()->route('menus.edit'),
												 DeleteAction::make()->route('menus.destroy'),
											])
				 ->addBulkAction(DeleteBulkAction::make()->permission('menus.destroy'))
				 ->addBulkChanges([
														 NameBulkChange::make(),
														 StatusBulkChange::make(),
														 CreatedAtBulkChange::make(),
													])
				 ->queryUsing(function (Builder $query) {
						$query
							 ->select([
													 'id',
													 'name',
													 'created_at',
													 'status',
												]);
				 });
	 }
}
