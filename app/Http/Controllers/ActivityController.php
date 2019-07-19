<?php

namespace App\Http\Controllers;

use App\Http\Controllers\WeChat\WeChatController;
use App\Model\AcitvityChannelDetail;
use App\Model\Activity;
use App\Model\Channel;
use App\Model\Orgs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;


class ActivityController extends Controller
{
    protected $file_url = '/www/xiaoyi_front_j/dist/static/';

    public function search (Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $type = $request->input('type');
        $status = $request->input('status');
        $page = $request->input('page',1);
        $page = $page - 1;
        $limit = $request->input('limit',10);
        $offset = $limit * $page;
        $where[] = ['is_del','=',1];
        if($name)
            $where[] = ['name','=',$name];
        if($id)
            $where[] = ['id','=',$id];
        if($type)
            $where[] = ['type','=',$type];
        if($status)
            $where[] = ['status','=',$status];
        if(ORGCODE)
            $where[] = ['org_code','=',ORGCODE];
        $arr = [
            'id'=>$id,
            'limit'=>$limit,
            'offset'=>$offset,
            'where'=>$where
        ];
        try{

            $validator = Validator::make($request->all(), [
                'id' => 'integer',
                'name' => 'string',
                'class' => 'digits:1',
                'online' => 'digits:1',
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());

            $result = Activity::search($arr);
            if($result['count']==0)
                throw new \Exception('暂无数据');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>['data'=>$result['data'],'count'=>$result['count']]]);
    }

    public function edit (Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $online = $request->input('online');
        $class = $request->input('class');

        $arr = [
            'id'=>$id,
            'name'=>$name,
            'class'=>$class,
            'online'=>$online,
            'updated_at'=>Carbon::now(),
        ];

        try{
            $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'required|string',
            'class' => 'required|string',
            'classId' => 'required|integer',
            'type' => 'required|integer',
            'channel' => 'required|string',
            'channel_id' => 'required|integer',
            'online' => 'required|digits:1',
        ]);
            if($validator->fails())
                throw new \Exception($validator->errors());
            $result = Activity::edit($arr);
            if(!$result)
                throw new \Exception('编辑失败');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }

    public function add (Request $request)
    {
        $name = $request->input('name','测试活动');
        $class = $request->input('class','素质教育');
        $classId = $request->input('classId',1); //类目id
        $online = $request->input('online',1);
        $type = $request->input('type',1);
        $content = $request->input('content');
//        $rule = $request->input('rule');
        $creator = $request->input('creator','sys');//创建人
        $finnal = "<!DOCTYPE html><html lang=\"en\"><head><meta charset=\"UTF-8\"><title>Title</title></head><body><form action=\"http://xiaoyiapi.edutage.com.cn/activity/record/add\" method=\"post\">
  <p>First name: <input type=\"text\" name=\"t1\" /></p >
  <p>Last name: <input type=\"text\" name=\"t2\" /></p ><input type=\"hidden\" name=\"act_id\" value=\"XXX\" /><input type=\"hidden\" name=\"rule\" value='{\"t1\":\"student_name\",\"t2\":\"parent_name\"}' />
  <input type=\"submit\" value=\"Submit\" />
</form>";
        $finnal .= $content;
        $finnal .= "<img src='http://exam.edutage.com.cn/static/images/banner.png'></body></html>";

        $arr = [
            'name'=>$name,
            'content'=>$finnal,
            'class'=>$class,
            'classId'=>$classId,
//            'rule'=>$rule,
            'online'=>$online,
            'type'=>$type,
            'creator'=>$creator,
            'org_code'=>ORGCODE,
            'updated_at'=>Carbon::now(),
            'created_at'=>Carbon::now(),
        ];

        try{
            $validator = Validator::make($request->all(), [
//                'name' => 'required|string',
//                'class' => 'required|string',
//                'classId' => 'required|integer',
                'content' => 'required',
//                'type' => 'required|integer',
//                'online' => 'required|digits:1',
//                'creator'=>'required|string',
//                'rule'=>'required'
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());

            $act_id = Activity::add($arr);

            if(!$act_id)
                throw new \Exception('新增失败');

            $finnal = htmlspecialchars_decode($finnal);

//            $content = trim($content,'<pre class="ql-syntax" spellcheck="false"><!DOCTYPE html></pre><p><br></p >');

            $content = $this->getContent($act_id,$finnal);
            $this->generate($act_id,$content);

            $where = [];

            $where[] = ['is_del','=',1];

            if(ORGCODE){
                $where[] = [
                    'org_code',
                    '=',
                    ORGCODE,
                ];
            }


            $res = Channel::where($where)->get();
            if(!$res->count()){
                throw new \Exception('请先添加活动渠道');
            }

            foreach ($res as $key=>$value)
            {
                $detail[] = [
                    'act_id'=>$act_id,
                    'channel_name'=>$value->channel,
                    'channel_id'=>$value->id,
                    'org_code'=>$value->org_code,
                    'url'=>'http://xiaoyi.edutage.com.cn'.'/static/'.$act_id.'.html?'.base64_encode($value->id),
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];
            }

            $res = AcitvityChannelDetail::insert($detail);

            if(!$res){
                throw new \Exception('新增渠道数据失败');
            }

//            $wechat = new WeChatController();
//            $res = $wechat->sendToAll($name,$content);
//            if($res['code'] != 200){
//                throw new \Exception($res['msg']);
//            }

            DB::commit();

        }catch(\Exception $e){
            DB::rollback();  //回滚
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'act_id'=>$act_id]);
    }

    public function del(Request $request)
    {
        $ids  = $request->input('ids');

        try{
            $validator = Validator::make($request->all(), [
                'ids' => 'required',
            ]);

            $id_arr = json_decode($ids,true);

            if($validator->fails())
                throw new \Exception($validator->errors());

            $res = Activity::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }

    public function get(Request $request)
    {
        $id = $request->input('id');

        $where[] = ['is_del','=',1];

        if($id)
            $where[] = ['id','=',$id];

        try{

            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());

//            $result = AcitvityChannelDetail::where($where)->get();

//            $result = Activity::where($where)->get();

            $result = Channel::where('org_code',ORGCODE)->get();

            if(!$result->count())
                throw new \Exception('暂无数据');

            foreach ($result as $key=>&$value)
            {
                $value->url = 'http://xiaoyi.edutage.com.cn'.'/static/'.$id.'.html?'.base64_encode($value->id);
                $value->channel_name = $value->channel;
            }

        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200,'data'=>['data'=>$result]]);
    }


    public function generate($act_id,$content)
    {
        ob_start();
        ob_end_clean();

        $fileName = $this->file_url.$act_id.'.html';
//        //写入文件
        $fp = fopen($fileName,'w');
        fwrite($fp,$content) or die('写文件错误');
        return true;
    }



        //获取html 追加input代码
    public function getContent($act_id,$content)
    {

        $content =  str_replace('XXX',$act_id,$content);
        $content .= "<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement(\"script\");
  hm.src = \"https://hm.baidu.com/hm.js?c0125abeacd238385a1914148c58a244\";
  var s = document.getElementsByTagName(\"script\")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>";
        return $content;
    }


}
