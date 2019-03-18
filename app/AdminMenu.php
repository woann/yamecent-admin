<?php

namespace App;

use App\Scopes\SortScope;
use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    protected $fillable = ['name', 'url', 'icon', 'sort', 'pid'];
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SortScope);
    }
    //
    public function roles()
    {
        return $this->belongsToMany(AdminRole::class);
    }

    /**
     * 无限分级菜单，获取下一级菜单
     *
     */
    public function children()
    {
        return $this->hasMany(self::class, 'pid');
    }

    /**
     * 无限分级菜单，获取上一级菜单
     *
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'pid');
    }
}
