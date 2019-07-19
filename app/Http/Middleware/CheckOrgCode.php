<?php

namespace App\Http\Middleware;

use App\Model\Orgs;
use App\Model\Students;
use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class CheckOrgCode
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
       
        define('ORGCODE',Session::get('org_code'));
        return $next($request);
    }


}
