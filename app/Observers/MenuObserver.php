<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Menu;

class MenuObserver
{
    public function created(Menu $menu): void
    {
        ActivityLog::log(
            action:      'created',
            model:       $menu,
            description: "Menu baru \"{$menu->name}\" ditambahkan.",
            newValues:   $menu->only(['name', 'price', 'category_id', 'is_active']),
        );
    }

    public function updated(Menu $menu): void
    {
        $dirty = $menu->getDirty();
        if (empty($dirty)) return;

        $old = array_intersect_key($menu->getOriginal(), $dirty);

        ActivityLog::log(
            action:      'updated',
            model:       $menu,
            description: "Menu \"{$menu->name}\" diperbarui.",
            oldValues:   $old,
            newValues:   $dirty,
        );
    }

    public function deleted(Menu $menu): void
    {
        ActivityLog::log(
            action:      'deleted',
            model:       $menu,
            description: "Menu \"{$menu->name}\" dihapus.",
        );
    }
}
