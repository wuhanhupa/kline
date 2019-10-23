<?php

namespace App\Http\Middleware;

use Closure;

/**
 * 登录中间件
 * 进入页面需要登录验证
 * Class CheckLogin
 * @package App\Http\Middleware
 */
class CheckLogin
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
        if (session('userInfo') == null) {
            return redirect('login');
        }

        return $next($request);
    }
}
