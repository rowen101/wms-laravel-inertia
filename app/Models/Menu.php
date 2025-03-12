<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "app_MenuMaster";
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_menu');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'ParentMenuID');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'ParentMenuID');
    }
}
