<?php

namespace App\Http\Middleware;

use App\Utility\Install;
use Closure;
use Illuminate\Container\Container;

class CheckInstall
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
        if (Install::hasLock()) {
            return $next($request);
        } else {
            return Container::getInstance()
                ->make('redirect')
                ->route('installView');
        }
    }
}
