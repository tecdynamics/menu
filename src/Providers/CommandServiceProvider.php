<?php

namespace Tec\Menu\Providers;

use Tec\Base\Supports\ServiceProvider;
use Tec\Menu\Commands\ClearMenuCacheCommand;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            ClearMenuCacheCommand::class,
        ]);
    }
}
