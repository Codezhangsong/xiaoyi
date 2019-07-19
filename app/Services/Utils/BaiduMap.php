<?php


namespace App\Services\Utils;


class BaiduMap
{
   protected static $ak = 'P6jpMfCRC1cV5Af4kt2Tav3uEZkIGqN9';

//   protected static $sk = 'ueA0vEgfgWywmG58ddbgpykQWcGdyVxS';

    /***
     * @param $query
     * @param $region
     * @return array
     */
    public static function search($query, $region)
    {
        $url = "http://api.map.baidu.com/place/v2/search"; //GET请求;

        $querystring_arrays = [
            'query'=>$query,
            'region'=>$region,
            'output'=>'json',
            'page_size'=>1,
            'page_num'=>0,
            'ak'=>self::$ak,
        ];
        $res = Http::get($url, $querystring_arrays);

        if(!isset($res['data']['results'][0]['location']))
            return [];


        return $res['data']['results'][0]['location'];
    }

//    protected static function caculateAKSN($sk, $uri, $querystring_arrays, $method = 'GET')
//    {
//        if ($method === 'POST'){
//            ksort($querystring_arrays);
//        }
//        $querystring = http_build_query($querystring_arrays);
//        return md5(urlencode($uri.'?'.$querystring.$sk));
//    }
}