<?php


namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Validator;

class WebToken
{
    protected $key = 'edutage520';

    public function handle($request, Closure $next)
    {
        try{
//            $now = Carbon::now();
//            $timeStamp = $request->input('timeStamp');
//            $sign = $request->input('token');
//
//            $parameter = $request->input();
//
//            $validator = Validator::make($request->all(),[
//                'timeStamp'=>'required|digits:10',
//                'token'=>'required|string',
//            ]);
//
//            if($validator->fails())
//                throw new \Exception($validator->errors());
//
//            unset($parameter['token']);
//
//            $local_sign = $this->createSign($parameter);
//
//            if($local_sign != $sign)
//                throw new \Exception('签名错误');
//            if($now - $timeStamp > 1800)
//                throw new \Exception('请求超时');

            $result_data = $next($request);

            return $result_data;
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }
    }

    public function createSign($parameter)
    {
        sort($parameter);
        $str = '';
        foreach ($parameter as $key =>$value)
        {
            $str .= $key .$value;
        }

        $str .= $this->key;

        $sign = md5($str);

        return $sign;
    }

}