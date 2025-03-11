<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_menu');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_menu');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_menu');
    }
}
