<?php

namespace Tec\Menu\Listeners;

use Tec\Base\Contracts\BaseModel;
use Tec\Base\Events\DeletedContentEvent;
use Tec\Menu\Facades\Menu;
use Tec\Menu\Models\MenuNode;

class DeleteMenuNodeListener
{
    public function handle(DeletedContentEvent $event): void
    {
        if (
            ! $event->data instanceof BaseModel ||
            ! in_array($event->data::class, Menu::getMenuOptionModels())
        ) {
            return;
        }

        MenuNode::query()
            ->where([
                'reference_id' => $event->data->getKey(),
                'reference_type' => $event->data::class,
            ])
            ->delete();
    }
}
