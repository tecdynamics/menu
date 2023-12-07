<?php

namespace Tec\Menu\Models;

use Tec\Base\Casts\SafeContent;
use Tec\Base\Enums\BaseStatusEnum;
use Tec\Base\Models\BaseModel;
use Tec\Base\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends BaseModel
{
    use HasSlug;

    protected $table = 'menus';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'image',
        'template'
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
    ];

    protected static function booted(): void
    {
        static::deleting(function (self $model) {
            $model->menuNodes()->delete();
            $model->locations()->delete();
        });

        self::saving(function (self $model) {
            $model->slug = self::createSlug($model->slug, $model->getKey());
        });
    }

    public function menuNodes(): HasMany
    {
        return $this->hasMany(MenuNode::class, 'menu_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(MenuLocation::class, 'menu_id');
    }
}
