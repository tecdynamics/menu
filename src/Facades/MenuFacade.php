<?php

namespace Tec\Menu\Facades;

use Illuminate\Support\Facades\Facade;
use Tec\Menu\Menu as BaseMenu;

/**
 * @method static bool hasMenu(string $slug)
 * @method static array recursiveSaveMenu(array $menuNodes, string|int $menuId, string|int $parentId)
 * @method static \Tec\Menu\Models\MenuNode getReferenceMenuNode(array $item, \Tec\Menu\Models\MenuNode $menuNode)
 * @method static \Tec\Menu\Menu addMenuLocation(string $location, string $description)
 * @method static array getMenuLocations()
 * @method static \Tec\Menu\Menu removeMenuLocation(string $location)
 * @method static string renderMenuLocation(string $location, array $attributes = [])
 * @method static bool isLocationHasMenu(string $location)
 * @method static void load(bool $force = false)
 * @method static string|null generateMenu(array $args = [])
 * @method static void registerMenuOptions(string $model, string $name)
 * @method static string|null generateSelect(array $args = [])
 * @method static \Tec\Menu\Menu addMenuOptionModel(string $model)
 * @method static array getMenuOptionModels()
 * @method static \Tec\Menu\Menu setMenuOptionModels(array $models)
 * @method static \Tec\Menu\Menu clearCacheMenuItems()
 * @method static void useMenuItemIconImage()
 * @deprecated
 * @see \Tec\Menu\Menu
 */
class MenuFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseMenu::class;
    }
}
