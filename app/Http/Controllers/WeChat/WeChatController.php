<?php


namespace App\Http\Controllers\WeChat;


use App\Http\Controllers\Components\UploadController;
use App\Model\Orgs;
use App\Model\WechatPlatform;
use App\Services\WeChatAuth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use TheSeer\Tokenizer\Exception;

class WeChatController
{

    /*** 图文消息根据标签id群发接口
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendAll(Request $request)
    {
        try{
            $title = $request->input('title');
            $tag_id = $request->input('tag_id',1);
            $content = $request->input('content');
            $validator = Validator::make($request->all(),[
                'title'=>'required|string',
                'content'=>'required|string',
                'tag_id'=>'integer',
            ]);

            if($validator->fails()){
                throw new \Exception($validator->errors());
            }

            $res = $this->uploadNews($title,$content);

            if($res['code']!=200){
                throw new \Exception($res['msg']);
            }

            if(!isset($res['data']['media_id']))
                throw new \Exception($res['data']['errmsg']);

            $media_id = $res['data']['media_id'];

            if(isset($tag_id) && !empty($tag_id) ){
                $parameter = [
                    'filter'=>[
                        'is_to_all'=>false,
                        'tag_id'=>1,
                    ],
                    'mpnews'=>[
                        'media_id'=>$media_id,
                    ],
                    'msgtype'=>'mpnews',
                    'send_ignore_reprint'=>0
                ];
            }else{
                $parameter = [
                    'filter'=>[
                        'is_to_all'=>true,
                    ],
                    'mpnews'=>[
                        'media_id'=>$media_id,
                    ],
                    'msgtype'=>'mpnews',
                    'send_ignore_reprint'=>0
                ];
            }

            $wechat = new WeChatAuth();
            $res = $wechat->sendAll($parameter);
            if(isset($res['data']['errcode']) && $res['data']['errcode']!=0){
                throw new \Exception($res['data']['errmsg']);
            }

            WechatPlatform::insert([
                'title'=>$title,
                'content'=>$content,
                'media_id'=>$media_id,
                'result'=>1,
                'return_message'=>$res['data']['errmsg'],
                'org_code'=>ORGCODE,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);

        }catch (\Exception $e){
            WechatPlatform::insert([
                'title'=>$title,
                'content'=>$content,
                'result'=>2,
                'return_message'=>$e->getMessage(),
                'org_code'=>ORGCODE,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }

        return response()->json($res);
    }


    public function sendToAll($title,$content)
    {
        try{
            $res = $this->uploadNews($title,$content);
            if($res['code']==500){
                throw new \Exception($res['msg']);
            }
            if(!isset($res['data']['media_id']))
                throw new \Exception($res['data']['errmsg']);

            $media_id = $res['data']['media_id'];

            if(isset($tag_id) && !empty($tag_id) ){
                $parameter = [
                    'filter'=>[
                        'is_to_all'=>true,
//                        'tag_id'=>1,
                    ],
                    'mpnews'=>[
                        'media_id'=>$media_id,
                    ],
                    'msgtype'=>'mpnews',
                    'send_ignore_reprint'=>0
                ];
            }else{
                $parameter = [
                    'filter'=>[
                        'is_to_all'=>true,
                    ],
                    'mpnews'=>[
                        'media_id'=>$media_id,
                    ],
                    'msgtype'=>'mpnews',
                    'send_ignore_reprint'=>0
                ];
            }

            $wechat = new WeChatAuth();
            $res = $wechat->sendAll($parameter);
            if(isset($res['data']['errcode']) && $res['data']['errcode']!=0){
                throw new \Exception($res['data']['errmsg']);
            }

            WechatPlatform::insert([
                'title'=>$title,
                'content'=>$content,
                'media_id'=>$media_id,
                'result'=>1,
                'return_message'=>$res['data']['errmsg'],
                'org_code'=>ORGCODE,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);

        }catch (\Exception $e){
            WechatPlatform::insert([
                'title'=>$title,
                'content'=>$content,
                'result'=>2,
                'return_message'=>$e->getMessage(),
                'org_code'=>ORGCODE,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
//            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
            return ['code'=>500,'msg'=>$e->getMessage()];
        }

        return ['code'=>200];
    }

    /***
     * @param Request $request 根据openid图文消息群发接口
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendByOpenId(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'title'=>'required|string',
                'content'=>'required|string',
                'touser'=>'required'
            ]);

            if($validator->fails()){
                throw new \Exception($validator->errors());
            }

            $res = $this->uploadNews($request);

            if(!isset($res['data']['media_id']))
                throw new \Exception($res['errmsg']);
            $touser = $request->input('touser');
            if(empty($touser))
                throw new \Exception('open_id不能为空');
            $open_ids = json_decode($touser);

            $media_id = $res['data']['media_id'];
            $parameter = [
                'touser'=>$open_ids,
                'mpnews'=>[
                    'media_id'=>$media_id,
                ],
                'msgtype'=>'mpnews',
                'send_ignore_reprint'=>0
            ];

            $wechat = new WeChatAuth();

            $res = $wechat->sendByOpenId($parameter);

        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }

        return response()->json($res);
    }

    public function searchOpenId()
    {
        $wechat = new WeChatAuth();

        $res = $wechat->getOpenId();

        return response()->json($res);
    }

    public function getTag()
    {
        $wechat = new WeChatAuth();

        $res = $wechat->getTag();

        return response()->json(['code'=>200,'msg'=>'ok','data'=>$res]);
    }

    /** //上传图文消息
     * @param Request $request
     * [
     *   title
     *   content
     *   thumb_url
     * ]
     * @return array
     */
    public function uploadNews($title,$content)
    {
//        $title = $request->input('title');
//
//        $content = $request->input('content');

        try{
            $res = $this->getContent($content);
            if($res['code'] == 500){
                throw new \Exception($res['msg']);
            }
            $result = $this->uploadThumb($res['thumb_url']);
            if(!isset($result['data']['thumb_media_id']))
                throw new \Exception('thumb_media_id 为空');
            $thumb_media_id = $result['data']['thumb_media_id'];

            $param = [
                'articles'=>[
                    [
                        'thumb_media_id'=>$thumb_media_id,
                        'title'=>$title,
                        'content'=>$res['content'],
                    ],
                ],
            ];

            $wechat = new WeChatAuth();

            return $wechat->uploadNews($param);
        }catch (\Exception $e){
            return ['code'=>500,'msg'=>$e->getMessage()];
        }

    }



    public function getImageUrl($url)
    {
//        $url = public_path('/uploads/img/1557128112.png');
//        $url = public_path($url);
        $wechat = new WeChatAuth();
        $res = $wechat->uploadImg($url);
        return $res;
    }

//图片image、
    public function uploadImage($url)
    {
//        $url = public_path('/uploads/img/1557128112.png');
        $wechat = new WeChatAuth();
        $res = $wechat->uploadSourse($url,$type='image');

        return $res;
    }

//视频（video）
    public function uploadVideo($url)
    {
//        $url = public_path('/uploads/video/test.mp4');
        $wechat = new WeChatAuth();
        $res = $wechat->uploadSourse($url,$type='video');
        return $res;
    }

//缩略图（thumb）
    public function uploadThumb($url)
    {
//        $url = public_path($url);
        $wechat = new WeChatAuth();
        $res = $wechat->uploadSourse($url,$type='thumb');

        return $res;
    }


    //获取html 处理img标签
    public function getContent($content)
    {
//        $html = '<p>asfasfasf</p><p><img src="/uploads/ueditor/php/upload/image/20190510/1557452398101914.png" title="1557452398101914.png" alt="屏幕快照 2019-05-06 下午4.26.03.png"/></p><p><br/></p><p>asdasdasdasdasdsadflkjalsfdfilsdj<img src="/uploads/ueditor/php/upload/image/20190510/1557452408496596.png" title="1557452408496596.png" alt="屏幕快照 2019-05-04 上午9.31.14.png"/></p>"';
        try{
            $pattern_src = '/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/';

            preg_match_all($pattern_src, $content, $match_src1);

            if(!empty($match_src1)){
                $arr_src1 = $match_src1[1];
                $thumb = $arr_src1[0];
                foreach ($arr_src1 as $key=>$value)
                {
                    $url ='';
                    $res = $this->getImageUrl($value);
                    if($res['code'] != 200)
                    {
                        throw new \Exception($res['msg']);
                    }
                    if(isset($res['data']['url']))
                        $url = $res['data']['url'];
                    $content = str_replace($value,$url,$content);
                }
            }
        }catch (\Exception $e){
            return ['code'=>500,'msg'=>$e->getMessage()];
        }

        return ['code'=>200,'content'=>$content,'thumb_url'=>$thumb];
    }


}