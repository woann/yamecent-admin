<?php
namespace App\Utility;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class Rbac
{
    public static function getAllRoutes(): Collection
    {
        return (new Collection(Route::getRoutes()))
            ->filter(function ($route) {
                $actions = $route->getAction();
                return isset($actions['as']) && $actions['as'] === 'rbac';
            })
            ->map(function ($route) {
                $method          = $route->methods[0];
                $route->rbacRule = "{$method}:{$route->uri}";
                return $route;
            });
    }
}
