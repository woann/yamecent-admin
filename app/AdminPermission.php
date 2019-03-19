<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AdminPermission extends Model
{
    protected $fillable = ['name', 'routes'];
    //
    public function roles()
    {
        return $this->belongsToMany(AdminRole::class);
    }

    protected function setRoutesAttribute($routes)
    {
        if (!($routes instanceof Collection)) {
            $routes = new Collection($routes);
        }
        $this->attributes['routes'] = $routes->implode(',');
    }

    protected function getRoutesAttribute($routeStr)
    {
        return new Collection(explode(',', $routeStr));
    }
}
