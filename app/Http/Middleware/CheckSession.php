<?php

namespace App\Http\Middleware;

use App\AdminUser;
use Closure;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->session()->has('admin')) {
            return redirect('/login');
        }
        $admin = $request->session()->get('admin');
        if (!($admin instanceof AdminUser)) {
            return redirect('/login');
        }
        return $next($request);
    }
}
