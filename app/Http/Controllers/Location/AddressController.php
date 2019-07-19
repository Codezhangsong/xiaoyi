<?php

namespace App\Http\Controllers\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function getProvince()
    {
        $res = DB::table('province')->get();

        return response()->json(['code'=>200,'data'=>$res]);
    }

    public function getCity(Request $request)
    {
        $provinceCode = $request->input('provinceCode');

        try{
            $validator = Validator::make($request->all(), [
                'provinceCode' => 'required|integer',
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());
            $res = DB::table('city')->where('PROVINCE_CODE',$provinceCode)->get();
            if(empty($res))
                throw new \Exception('暂无数据');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200,'data'=>$res]);
    }


    public function getArea(Request $request)
    {
        $cityCode = $request->input('cityCode');

        try{
            $validator = Validator::make($request->all(), [
                'cityCode' => 'required|integer',
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());
            $res = DB::table('area')->where('CITY_CODE',$cityCode)->get();
            if(empty($res))
                throw new \Exception('暂无数据');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200,'data'=>$res]);
    }

    public function getStreet(Request $request)
    {
        $areaCode = $request->input('areaCode');

        try{
            $validator = Validator::make($request->all(), [
                'areaCode' => 'required|integer',
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());
            $res = DB::table('street')->where('AREA_CODE',$areaCode)->get();
            if(empty($res))
                throw new \Exception('暂无数据');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200,'data'=>$res]);
    }
}
