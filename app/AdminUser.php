<?php

namespace App;

use App\AdminRole;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class AdminUser extends Model
{
    //
    protected $fillable = ['avatar', 'nickname', 'account', 'password'];
    protected $hidden   = ['password'];

    public function roles()
    {
        return $this->belongsToMany(AdminRole::class);
    }

    public function getMenus()
    {
        $roles        = $this->roles;
        $hasSuperRole = false;
        $roles->each(function ($role) use (&$hasSuperRole) {
            if ($role->id === 1) {
                $hasSuperRole = true;
                return false;
            }
        }, $roles);
        $dealTopMenuFunc = function ($topMenu) {
            $topMenu->hasChild = $topMenu->children->isNotEmpty();
            return $topMenu;
        };
        if ($hasSuperRole) {
            $menus = AdminMenu::where('pid', 0)
                ->get()
                ->map($dealTopMenuFunc);
        } else {
            $menus = $roles
                ->map(function ($role) {
                    return $role->menus()->where('pid', 0)->get();
                })
                ->collapse()
                ->map($dealTopMenuFunc);
        }
        return $menus;
    }

    public function getPermissionRoutes()
    {
        if ($this->hasSuperRole()) {
            $permissions = (new Collection(Container::getInstance()->routes->getRoutes()))
                ->filter(function ($route) {
                    $actions = $route->getAction();
                    return isset($actions['as']) && $actions['as'] === 'rbac';
                })
                ->map(function ($route) {
                    return $route->uri;
                });

        } else {
            $permissions = $this->roles->map(function ($role) {
                return $role->permissions;
            })
                ->collapse()
                ->map(function ($permission) {
                    return $permission->routes;
                })
                ->collapse();
        }
        return $permissions;
    }

    public function hasSuperRole()
    {
        $hasSuperRole = false;
        $this->roles->each(function ($role) use (&$hasSuperRole) {
            if ($role->id === 1) {
                $hasSuperRole = true;
                return false;
            }
        });
        return $hasSuperRole;
    }

    public static function isExist(string $account)
    {
        $instance = new static;
        return $instance->where('account', $account)->count() > 0;
    }

    public function isExistForUpdate(string $account)
    {
        return $this->where('id', '!=', $this->id)
            ->where('account', $account)
            ->count() > 0;
    }

    protected function setPasswordAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return;
        }
        $this->attributes['password'] = Hash::make($value);
    }
}
