<?php

namespace Phambinh\Appearance\Supports\Traits;

trait NavigationMenu
{
    public static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            if ($model->inMenuItem()) {
                $model->syncMenuItem();
            }
        });
    }

    public function menuItems()
    {
        return $this->hasMany('Phambinh\Appearance\Models\MenuItem', 'object_id')->where('type', __CLASS__);
    }

    public function scopeAddToMenu($query, $menu_id, $params = [])
    {
        $data = array_merge([
            'type' => __CLASS__,
            'object_id' => $this->id,
            'url' => $this->menuUrl(),
            'title' => $this->menuTitle(),
            'menu_id' => $menu_id,
        ], $params);

        return $this->menuItems()->insert($data);
    }

    public function syncMenuItem()
    {
        $menuItems = $this->menuItems()->update([
            'url' => $this->menuUrl(),
        ]);
    }

    public function inMenuItem()
    {
        return $this->menuItems()->exists();
    }
}
