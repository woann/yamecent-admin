<?php

namespace App\Http\Middleware;
use Closure;
use DB;
class Rbac{
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
        //先判断是否登录
        if(!$request->session()->has('admin')) {
            return redirect('/login');
        }
        $session = $request->session()->get('admin');

        //获取当前路由
        $route_current = \Route::current()->uri;
        //获取当前管理员角色
        $role_id = $session->id;
        //通过角色获取当前管理员所有权限
        $permission_list = DB::table('admin_role_permission as rp')
            ->leftJoin('admin_permission as p','p.id','=','rp.permission_id')
            ->where('rp.role_id',$role_id)
            ->get();
        $route = [];
        foreach ($permission_list as $k=>$v){
            $routes = explode(',',$v->route);
            $route = array_merge($route,$routes);
        }
        if(!in_array($route_current,$route)){
            return redirect("403");
        }
        //判断当前路由是否属于该管理员的权限
        return $next($request);
    }
}