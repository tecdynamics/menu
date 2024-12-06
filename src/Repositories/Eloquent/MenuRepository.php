<?php

namespace Tec\Menu\Repositories\Eloquent;

use Tec\Base\Models\BaseModel;
use Tec\Menu\Models\Menu;
use Tec\Menu\Repositories\Interfaces\MenuInterface;
use Tec\Support\Repositories\Eloquent\RepositoriesAbstract;

class MenuRepository extends RepositoriesAbstract implements MenuInterface
{
    public function findBySlug(string $slug, bool $active, array $select = [], array $with = []): ?BaseModel
    {
        $data = $this->model->where('slug', $slug);

        if ($active) {
            $data = $data->wherePublished();
        }

        if (! empty($select)) {
            $data = $data->select($select);
        }

        if (! empty($with)) {
            $data = $data->with($with);
        }

        $data = $this->applyBeforeExecuteQuery($data, true)->first();

        $this->resetModel();

        return $data;
    }

    public function createSlug(string $name): string
    {
        return Menu::createSlug($name, null);
    }
}
