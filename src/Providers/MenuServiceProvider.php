<?php

namespace Tec\Menu\Providers;

use Tec\Base\Facades\DashboardMenu;
use Tec\Base\Supports\ServiceProvider;
use Tec\Base\Traits\LoadAndPublishDataTrait;
use Tec\Menu\Models\Menu as MenuModel;
use Tec\Menu\Models\MenuLocation;
use Tec\Menu\Models\MenuNode;
use Tec\Menu\Repositories\Eloquent\MenuLocationRepository;
use Tec\Menu\Repositories\Eloquent\MenuNodeRepository;
use Tec\Menu\Repositories\Eloquent\MenuRepository;
use Tec\Menu\Repositories\Interfaces\MenuInterface;
use Tec\Menu\Repositories\Interfaces\MenuLocationInterface;
use Tec\Menu\Repositories\Interfaces\MenuNodeInterface;
use Tec\Theme\Events\RenderingAdminBar;
use Tec\Theme\Facades\AdminBar;

class MenuServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(MenuInterface::class, function () {
            return new MenuRepository(new MenuModel());
        });

        $this->app->bind(MenuNodeInterface::class, function () {
            return new MenuNodeRepository(new MenuNode());
        });

        $this->app->bind(MenuLocationInterface::class, function () {
            return new MenuLocationRepository(new MenuLocation());
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('packages/menu')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadHelpers()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        DashboardMenu::default()->beforeRetrieving(function () {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-core-menu',
                    'priority' => 2,
                    'parent_id' => 'cms-core-appearance',
                    'name' => 'packages/menu::menu.name',
                    'route' => 'menus.index',
                ]);
        });

        $this->app['events']->listen(RenderingAdminBar::class, function () {
            AdminBar::registerLink(
                trans('packages/menu::menu.name'),
                route('menus.index'),
                'appearance',
                'menus.index'
            );
        });

        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);
    }
}
