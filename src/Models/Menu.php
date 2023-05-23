<?php

namespace Tec\Menu\Models;

use Tec\Base\Enums\BaseStatusEnum;
use Tec\Base\Models\BaseModel;
use Tec\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tec\Menu\Enums\MenuTemplateEnum;

class Menu extends BaseModel
{

    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'status',
        'image',
        'template'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
        'template' => MenuTemplateEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Menu $menu) {
            MenuNode::where('menu_id', $menu->id)->delete();
        });
    }

    /**
     * @return HasMany
     */
    public function menuNodes()
    {
        return $this->hasMany(MenuNode::class, 'menu_id');
    }

    /**
     * @return HasMany
     */
    public function locations()
    {
        return $this->hasMany(MenuLocation::class, 'menu_id');
    }
}
