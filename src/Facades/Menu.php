<?php

namespace Tec\Menu\Facades;

use Illuminate\Support\Facades\Facade;
use Tec\Menu\Menu as MenuService;

class Menu extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return MenuService::class;
    }
}
