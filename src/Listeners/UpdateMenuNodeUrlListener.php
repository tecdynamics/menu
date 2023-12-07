<?php

namespace Tec\Menu\Listeners;

use Tec\Base\Facades\BaseHelper;
use Tec\Menu\Facades\Menu;
use Tec\Menu\Models\MenuNode;
use Tec\Slug\Events\UpdatedSlugEvent;
use Exception;

class UpdateMenuNodeUrlListener
{
    public function handle(UpdatedSlugEvent $event): void
    {
        if (! in_array(get_class($event->data), Menu::getMenuOptionModels())) {
            return;
        }

        try {
            $nodes = MenuNode::query()
                ->where([
                    'reference_id' => $event->data->getKey(),
                    'reference_type' => get_class($event->data),
                ])
                ->get();

            foreach ($nodes as $node) {
                $newUrl = str_replace(url(''), '', $node->reference->url);
                if ($node->url != $newUrl) {
                    $node->url = $newUrl;
                    $node->save();
                }
            }
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
