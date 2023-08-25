<?php

namespace Tec\Menu\Enums;

use Html;
use Tec\Base\Supports\Enum;

/**
 *  ****************************************************************
 *  *** DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER. ***
 *  ****************************************************************
 *  Copyright © 2023 TEC-Dynamics LTD <support@tecdynamics.org>.
 *  All rights reserved.
 *  This software contains confidential proprietary information belonging
 *  to Tec-Dynamics Software Limited. No part of this information may be used, reproduced,
 *  or stored without prior written consent of Tec-Dynamics Software Limited.
 * @Author    : Michail Fragkiskos
 * @Created at: 19/05/2023 at 13:54
 * @Class     : MenuTemplateEnum
 * @Package   : tec_new
 * @package Tec\Menu\Enums
 * @method static MenuTemplateEnum DEFAULT()
 * @method static MenuTemplateEnum WITH_MAIN_IMAGE_ONLY()
 * @method static MenuTemplateEnum WITH_ALL_IMAGES()
 */
class MenuTemplateEnum extends Enum {
    const DEFAULT = 'main-menu';
    const WITH_MAIN_IMAGE_ONLY = 'main_image_only';
    const WITH_ALL_IMAGES = 'all_images';

    /**
     * @return string
     */
    public function toHtml() {
        switch ($this->value) {
            case self::DEFAULT:
                return Html::tag('span', 'Default', ['class' => 'label-info status-label'])
                    ->toHtml();
            case self::WITH_MAIN_IMAGE_ONLY:
                return Html::tag('span', 'Only the Main Image', ['class' => 'label-warning status-label'])
                    ->toHtml();
            case self::WITH_ALL_IMAGES:
                return Html::tag('span', 'Include All Image', ['class' => 'label-success status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}
