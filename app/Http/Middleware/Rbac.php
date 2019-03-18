<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class Rbac
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $admin = $request->session()->get('admin');
        //获取当前管理员角色
        $roles = $admin->roles;
        if ($roles->isEmpty()) {
            return redirect('403');
        }
        //通过角色获取当前管理员所有权限
        $permissionRoutes = $admin->getPermissionRoutes();
        if ($permissionRoutes->contains(Route::current()->uri) === false) {
            return redirect('403');
        }
        //判断当前路由是否属于该管理员的权限
        return $next($request);
    }
}
