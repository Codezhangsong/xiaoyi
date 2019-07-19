<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/3
 * Time: 16:16
 */

namespace App\Services\Utils;
use GuzzleHttp\Client;

class Http
{
    public static $repetitions = 3;

    public static $secret='liveCourse!@#$%19';
    public static $des_key='tyxs9sx';


    /**
     * 请求接口GET
     * @param string $uri 接口地址
     * @param array $parameter 请求参数
     * @return array
     */
    public static function get($url,$query)
    {
        $client = new Client();

        try {
            $request = $client->get($url,[
                'query'=>$query,
                'timeout' => 10
            ]);

            $code = $request->getStatusCode();
            $content = $request->getBody()->getContents();
            if($code != 200)
                throw new \Exception('调用失败');
        } catch(\Exception $e) {
            $code = $e->getCode();
            $content = $e->getMessage();
            return ['code'=>$code,'msg'=>$content];
        }

        $arr = json_decode($content, true);
        return ['code'=>$code,'data'=>$arr];
    }


    /**
     * 请求接口POST
     * @param string $uri 接口地址
     * @param array $parameter 请求参数
     * @return array
     */
    public static function post($uri, $parameter = [])
    {
        $client = new Client();
        try {
                $request = $client->request('POST', $uri, [
                    'form_params' => $parameter,
                ]);
                $code = $request->getStatusCode();
                $content = $request->getBody()->getContents();
                if($code != 200)
                    throw new \Exception('调用失败');
            } catch(\Exception $e) {
                $code = $e->getCode();
                $content = $e->getMessage();
                return ['code'=>$code,'msg'=>$content];
            }

        $arr = json_decode($content, true);
        return ['code'=>$code,'data'=>$arr];
    }


    /**
     * 验证签名
     * @param array $parameter 参与验证数组
     * @return string
     */
    public static function createSignature($parameter)
    {

        ksort($parameter);
        $string = '';

        foreach ($parameter as $key => $value) {
            $string .= $key .'='. $value.'&';
        }
        $secret_string = $string. self::$secret;

        $md5 = md5($string . self::$secret);
        return $md5;
    }


    /**
     * 返回及日志记录
     * @param int $code 请求状态码
     * @param string $content 内容字符串
     * @param string $parameter 请求入参
     * @return array
     */
    private static function response($code, $content, $parameter)
    {


    }

    public static function des_ecb_encrypt($data, $key){
        return openssl_encrypt ($data, 'des-ecb', $key);
    }


}

