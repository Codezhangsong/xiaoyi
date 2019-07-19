<?php


namespace App\Services\Utils;


class BaiduTj
{
    /**
     * @param $url
     * @param $headersArr
     * @param $bodyArr
     * @return bool|mixed
     */
    public static function getData($url, $headersArr, $bodyArr)
    {
//        if (!isset($headersArr['type'], $headersArr['username'], $headersArr['password'], $headersArr['token'])) {
//            return false;
//        }
//        if (!isset($bodyArr['siteId'], $bodyArr['method'], $bodyArr['s_time'], $bodyArr['e_time'])) {
//            return false;
//        }
//        if (!isset($bodyArr['metrics'], $bodyArr['gran'], $bodyArr['max_results'])) {
//            return false;
//        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
//        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json' // 防止无法接收CURLOPT_POSTFIELDS内容
        ]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
            'header' => [
                'account_type' => $headersArr['type'],
                'username' => $headersArr['username'],
                'password' => $headersArr['password'],
                'token' => $headersArr['token'],
            ],
            'body' => [
                'siteId' => $bodyArr['siteId'],
                'method' => $bodyArr['method'],
                'start_date' => $bodyArr['s_time'],
                'end_date' => $bodyArr['e_time'],
//                'metrics' => $bodyArr['metrics'],
//                'gran' => $bodyArr['gran'],
                'max_results' => $bodyArr['max_results']
            ]
        ]));
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return false;
        }
        curl_close($curl);

        return json_decode($result, true);
    }

    public static function getList($url, $headersArr)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json' // 防止无法接收CURLOPT_POSTFIELDS内容
        ]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
            'header' => [
                'account_type' => $headersArr['type'],
                'username' => $headersArr['username'],
                'password' => $headersArr['password'],
                'token' => $headersArr['token'],
            ],
        ]));
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return false;
        }
        curl_close($curl);

        return json_decode($result, true);
    }
}