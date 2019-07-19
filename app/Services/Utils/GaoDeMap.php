<?php


namespace App\Services\Utils;


class GaoDeMap
{

    public static function search($parameter)
    {

        $url = 'https://restapi.amap.com/v3/geocode/geo?parameters';
        $parameter['key'] = '6e5e1e304aaa591b8e3c6372c3e391af';
        $res = Http::get($url,$parameter);
        $location = [];
        if($res['code']==200 && $res['data']['count']!=0){
            $arr = explode(',',$res['data']['geocodes'][0]['location']) ;
            $location['lat'] = $arr[0];
            $location['lng'] = $arr[1];
        }
        return $location;
    }
}