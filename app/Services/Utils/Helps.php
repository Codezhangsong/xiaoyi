<?php


namespace App\Services\Utils;;


class Helps
{
    protected static $key = 'liveCourse!@#$%19';
    /***
     * @param $birthday string Y-m-d 2019-01-09
     * @return int
     */
    public static function birthdayTransLate($birthday)
    {
        $age = strtotime($birthday);
        if($age === false){
            return false;
        }
        list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age));
        $now = strtotime("now");
        list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now));
        $age = $y2 - $y1;
        if((int)($m2.$d2) < (int)($m1.$d1)){
            $age -= 1;
        }
        return $age;
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
        $secret_string = $string. self::$key;

        var_dump($secret_string);

        $md5 = md5($string . self::$key);
        return $md5;
    }


    public static function des_ecb_encrypt($data, $key){
        //return openssl_encrypt ($data, 'des-ecb', $key);
          return bin2hex(openssl_encrypt($data, 'DES-ECB', $key, OPENSSL_RAW_DATA));
    }

    public static function check_mobile($mobile)
    {
        if(!preg_match('/^1([0-9]{9})/',$mobile)){
            return ['code'=>'500','msg'=>'请填写正确手机号'];
        }else{
            return ['code'=>200];
        }
    }

}