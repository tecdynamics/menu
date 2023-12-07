<?php

namespace Tec\Menu\Listeners;

use Tec\Base\Events\DeletedContentEvent;
use Tec\Menu\Facades\Menu;
use Tec\Menu\Models\MenuNode;

class DeleteMenuNodeListener
{
    public function handle(DeletedContentEvent $event): void
    {
        if (! in_array(get_class($event->data), Menu::getMenuOptionModels())) {
            return;
        }

        MenuNode::query()
            ->where([
                'reference_id' => $event->data->getKey(),
                'reference_type' => get_class($event->data),
            ])
            ->delete();
    }
}
