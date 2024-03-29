<?php

namespace Tec\Menu\Providers;

use Tec\Base\Events\DeletedContentEvent;
use Tec\Menu\Listeners\DeleteMenuNodeListener;
use Tec\Menu\Listeners\UpdateMenuNodeUrlListener;
use Tec\Slug\Events\UpdatedSlugEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UpdatedSlugEvent::class => [
            UpdateMenuNodeUrlListener::class,
        ],
        DeletedContentEvent::class => [
            DeleteMenuNodeListener::class,
        ],
    ];
}
