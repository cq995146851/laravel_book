<?php

namespace App\Http\Middleware;

use Closure;

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
        $member = $request->session()->get('member');
        if(empty($member)){
            $returnUrl = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/category';
            $request->session()->put('returnUrl',$returnUrl);
            return redirect('/login');
         }
        return $next($request);
    }
}
