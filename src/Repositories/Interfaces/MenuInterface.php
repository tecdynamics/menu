<?php

namespace Tec\Menu\Repositories\Interfaces;

use Tec\Base\Models\BaseModel;
use Tec\Support\Repositories\Interfaces\RepositoryInterface;

interface MenuInterface extends RepositoryInterface
{
    public function findBySlug(string $slug, bool $active, array $select = [], array $with = []): ?BaseModel;

    public function createSlug(string $name): string;
}
