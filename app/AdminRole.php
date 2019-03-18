<?php

namespace App;

use App\AdminUser;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    //
    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->belongsToMany(AdminUser::class);
    }

    public function menus()
    {
        return $this->belongsToMany(AdminMenu::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(AdminPermission::class);
    }
}
