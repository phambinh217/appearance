<?php

namespace Phambinh\Appearance\Support\Traits;

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
        return $this->hasMany('Phambinh\Appearance\MenuItem', 'object_id')->where('type', __CLASS__);
    }

    public function scopeAddToMenu($query, $menu_id, $params = [])
    {
        $data = array_merge([
            'type' => __CLASS__,
            'object_id' => method_exists($this, 'menuId') ? $this->menuId() : $this->id,
            'url' => $this->menuUrl(),
            'title' => method_exists($this, 'menuTitle') ? $this->menuTitle() : $this->title,
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
