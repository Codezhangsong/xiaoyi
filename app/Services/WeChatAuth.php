<?php


namespace App\Services;


use App\Model\Orgs;
use App\Services\Utils\Http;

class WeChatAuth
{
    public static $access_token;
    public static $get_token_url = 'https://api.weixin.qq.com/cgi-bin/token';

    public static $upload_img_url ='https://api.weixin.qq.com/cgi-bin/media/uploadimg';
    public function __construct()
    {
        if(self::$access_token == null){
            self::getAccessToken();
            var_dump('1',self::$access_token);
        }
    }


    public static function getAccessToken()
    {
       // $org = Orgs::where('org_code',ORGCODE)->first();
        try{
            // if(!$org->openId || !$org->secret){
            //     throw new \Exception('请设置微信OpenId');
            // }
            $query = [
                'grant_type'=>'client_credential',
//                'appid'=>$org->openId,
                'appid'=>'wxe4edbe2baf45e14e',
                'secret'=>'8efad6d1e1b55e25c7d1f18fb1ce8c56',
            ];

            $res = Http::get(self::$get_token_url,$query);
           
            if(isset($res['data']['access_token'])){
                self::$access_token = $res['data']['access_token'];
            }
        }catch (\Exception $e){
            return ['code'=>500,'msg'=>$e->getMessage()];
        }

        return ['code'=>200];

    }

    public function uploadNews($data)
    {
        $wechat_upload_url = 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token='.self::$access_token; //上传图文消息

        $postJson = urldecode(json_encode($data));
        $res=$this->https_request( $wechat_upload_url ,$postJson);

        return $res;
    }

    public function sendAll($para)
    {
        $wechat_upload_url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.self::$access_token; //标签群发图文消息

        $data = json_encode($para);
        $res=$this->https_request( $wechat_upload_url ,$data);

        return $res;

    }

    public function sendByOpenId($para)
    {
        $wechat_upload_url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.self::$access_token; //标签群发图文消息

        $data = json_encode($para);
        $res=$this->https_request( $wechat_upload_url ,$data);

        return $res;
    }

    public function getOpenId()
    {
        $wechat_upload_url = 'https://api.weixin.qq.com/cgi-bin/user/get'; //查询openid

        $query = [
            'access_token'=>self::$access_token,
            'next_openid'=>''
        ];
        $res = Http::get($wechat_upload_url,$query);

        return $res;
    }

    public function getTag()
    {
        $wechat_upload_url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.self::$access_token; //上传图文消息
        $res = $this->https_request($wechat_upload_url,[]);
        return $res;
    }

    //素材上传接口  //图片 //视频 //略图
    //10MB，支持MP4格式
   public function uploadSourse($url,$type)
   {
       $wechat_upload_url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.self::$access_token.'&type='.$type; //上传临时素材文件

    
       if(!file_exists($url)){
           return ['code'=>500,'msg'=>'文件不存在'];
       }

       $data = [
           'media'=>new \CURLFile($url),
       ];
       $res=$this->https_request( $wechat_upload_url ,$data);

       return $res;
   }

   public function uploadImg($url)
   {
       $wechat_upload_url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=".self::$access_token.'&type=image'; //获得image_url

       if(!file_exists($url)){
           return ['code'=>500,'msg'=>'文件不存在'];
       }
        $data = [
            'media'=>new \CURLFile($url),
        ];
       $res=$this->https_request( $wechat_upload_url ,$data);

       return $res;
   }

//   public function


    public function https_request($url ='' , $data)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);

        curl_setopt($curl, CURLOPT_URL, $url);
        if (!empty($data)) {
            //$data不为空，发送post请求
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data); //$data：数组
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT,"TEST");
        $result = curl_exec($curl);
        if($result === false) {
            return ['code'=>500,'msg'=>'error'.curl_errno($curl)];
        }

        $res=json_decode($result,true);

        return ['code'=>200,'data'=>$res];

    }
}