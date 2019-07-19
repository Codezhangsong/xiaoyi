<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

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
       
        $res = Session::has('adminname');
      
        if(!$res){
            return response()->json(['code'=>500,'msg'=>'请登陆']);
        }

        return $next($request);
    }
}
