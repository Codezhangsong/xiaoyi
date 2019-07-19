<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Components\UploadController;
use App\Model\Account;
use App\Model\Message;
use App\Model\Orgs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Redis;

class OrgController extends Controller
{
    public function add(Request $request)
    {
        $orgName = $request->input('orgName');
        $linkman = $request->input('linkman');
        $mobile = $request->input('mobile');
        $pwd = $request->input('pwd');
        $address = $request->input('address');
        $distinction = $request->input('distinction');
        $desc = $request->input('desc');
//        $app_id = $request->input('app_id');
//        $secret = $request->input('secret');

        $validator = Validator::make($request->all(),[
            'orgName' =>'required|string',
            'linkman' =>'required|string',
            'mobile' =>'required|digits:11',
            'pwd' =>'required',
            'address' =>'required|string',
            'distinction' =>'required|string',
            'desc' =>'required|string',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());

            $org_obj = Orgs::where('mobile',$mobile)->first();
            if($org_obj)
                throw new \Exception('该手机号已被注册');
            $res = UploadController::uploadImg($request);
            if($res['code']!=200)
                throw new \Exception('上传图片失败');
            $fileName = $res['fileName'];

            $arr = [
                'org_name'=>$orgName,
                'linkman'=>$linkman,
                'mobile'=>$mobile,
                'pwd'=>md5($pwd),
                'org_code'=>md5($mobile.time()),
                'address'=>$address,
                'distinction'=>$distinction,
                'desc'=>$desc,
                'pic_url'=>public_path('uploads/img/'.$fileName),
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
//                'secret'=>$secret,
//                'app_id'=>$app_id
            ];

            $result = Orgs::add($arr);
            if(!$result)
                throw new \Exception('插入失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');
        $orgName = $request->input('orgName');
        $linkman = $request->input('linkman');
        $pwd = $request->input('pwd');
        $mobile = $request->input('mobile');
        $address = $request->input('address');
        $distinction = $request->input('distinction');
        $desc = $request->input('desc');
        $useFlag = $request->input('useFlag',1);
        $picUrl = $request->input('picUrl','');

        $validator = Validator::make($request->all(),[
            'id' =>'required',
            'orgName' =>'required|string',
            'useFlag' =>'digits:1',
            'linkman' =>'required|string',
            'pwd' =>'required',
            'mobile' =>'required|digits:11',
            'address' =>'required|string',
            'distinction' =>'required|string',
            'desc' =>'required|string',
            'picUrl' =>'string',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $arr = [
                'id'=>$id,
                'org_name'=>$orgName,
                'linkman'=>$linkman,
                'pwd'=>$pwd,
                'mobile'=>$mobile,
                'address'=>$address,
                'distinction'=>$distinction,
                'desc'=>$desc,
                'use_flag'=>$useFlag,
                'pic_url'=>$picUrl,
                'updated_at'=>Carbon::now(),
            ];
            $result = Orgs::edit($arr);
            if(!$result)
                throw new \Exception('编辑失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }

    public function search(Request $request)
    {
        $orgName = $request->input('orgName');
        $useFlag = $request->input('useFlag');

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $page = $request->input('page',1);
        $page = $page -1;
        $limit = $request->input('limit',10);

        $where[] = ['is_del','=',1];

        $validator = Validator::make($request->all(),[
            'id' =>'integer',
            'orgName' =>'string',
            'useFlag' =>'integer|digits:1',
            'page'=>'integer',
            'limit'=>'integer',
        ]);

        if($orgName)
            $where[] = ['org_name','=',$orgName];
        if($useFlag)
            $where[] = ['use_flag','=',$useFlag];

        if($startDate != null && $endDate != null){
            $where[] = ['created_at','>=' ,$startDate];
            $where[] = ['created_at','<=' ,$endDate];
        }

        $offset = $page * $limit;

        $arr = [
            'where'=>$where,
            'limit'=>$limit,
            'offset'=>$offset
        ];
        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $result = Orgs::search($arr);
            if($result['count']==0)
                throw new \Exception('暂无数据');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'errors'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200,'data'=>['data'=>$result['data'],'count'=>$result['count']]]);
    }

    public function del(Request $request)
    {
        $ids  = $request->input('ids');
        $validator = Validator::make($request->all(), [
            'ids'=>'required',
        ]);
        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $id_arr = json_decode($ids,true);
            $res = Orgs::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200]);
    }

    public function checklogin(Request $request){

        $mchid = $request->input('mchid');
       // var_dump($request->all());
       
        $id = $request->input('id');

        

        $cacheName = 'deviceUUID:user'.$id;

      //  $num=rand(1,9999);
      //  var_dump($num);

       // var_dump($cacheName,1111,$id);
        //    Redis::set($cacheName,'456');
         $m = Redis::get($cacheName);

        // return response()->json(['code'=>500,'msg'=>$m,'cid'=>$mchid]);
        if($m!=$mchid){
         return response()->json(['code'=>500,'msg'=>$m]);
        }

    }

    public function check(Request $request){

       

     //   var_dump($request->all());
        

        $mobile = $request->input('mobile');
        $password = $request->input('pwd');

        $mchid = $request->input('mchid');

        $where[] = ['is_del','=',1];
        if($mobile)
            $where[] = ['mobile','=',$mobile];
        if($password)
            $where[] = ['pwd','=',$password];

        $arr = [
            'where'=>$where
        ];

        try{
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|string',
                'pwd' => 'required|string',
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());

            $res = Orgs::check($arr);

            //var_dump($res->id);exit;
           

           

            if(empty($res))
                throw new \Exception('账号密码不正确或者账户不存在');

            

        }catch(\Exception $e){
            return ['code'=>500,'msg'=>$e->getMessage()];
        }

        $sid = session_id();

        $org_code = Session::get($sid);
        
        Session::put($sid);


        if(!$org_code){
            Session::put('org_code',$res->org_code);
        }

        
       
       
        Session::put('adminname',$res->org_name);
        Session::save();

        $m = Session::get('org_code');

        
        $cacheName = 'deviceUUID:user'.$res->id;

       $m = Redis::get($cacheName);
       // $m='';

       // var_dump(11111,$m,$mchid);
        $deviceUUID = $this->getDeviceUUID($res->id); 
        $timeout = 10800; // 用户10十分钟无操作自动下线
        Redis::set($cacheName, $deviceUUID);
        Redis::expire($cacheName, $timeout);

       // var_dump($cacheName,$m,$mchid,1111);

    //     if($m){

    //         if($mchid == $m){

    //             $data = [
    //                 'id'=>$res->id,
    //                 'adminname'=>$res->org_name,
    //                 'sid'=>$sid,
    //                 'org_code'=>$res->org_code,
    //                 'mchid'=>$mchid

    //             ];

    //            return response()->json(['code'=>200,'data'=>$data]);

    //         }
    //     //     else{

              

    //     //             $data = [
    //     //                 'id'=>$res->id,
    //     //                 'adminname'=>$res->org_name,
    //     //                 'sid'=>$sid,
    //     //                 'org_code'=>$res->org_code,
    //     //                 'mchid'=>$mchid

    
    //     //             ];
    
    //     //          //  return response()->json(['code'=>500,'msg'=>'账号已在其他设备登录']);


            
                
    //     // }
    // }else{

    //     $deviceUUID = $this->getDeviceUUID(); 
    //             $timeout = 10800; // 用户10十分钟无操作自动下线
    //             Redis::set($cacheName, $deviceUUID);
    //             Redis::expire($cacheName, $timeout);

    //     $data = [
    //         'id'=>$res->id,
    //         'adminname'=>$res->org_name,
    //         'sid'=>$sid,
    //         'org_code'=>$res->org_code,
    //         'mchid'=>$deviceUUID

    //     ];

    //    return response()->json(['code'=>200,'data'=>$data]);



    // }





        // if($mchid){

           



        //             $data = [
        //                 'id'=>$res->org_code,
        //                 'adminname'=>$res->org_name,
        //                 'sid'=>$sid,
        //                 'org_code'=>$res->org_code,
        //                 'mchid'=>$mchid

        //             ];

        //          //   return response()->json(['code'=>200,'data'=>$data]);

        //     }else{
    
        //         $deviceUUID = $this->getDeviceUUID(); 
        //         $timeout = 100; // 用户10十分钟无操作自动下线
        //         Redis::set($cacheName, $deviceUUID);
        //         Redis::expire($cacheName, $timeout);


        //         $data = [
        //             'id'=>$res->org_code,
        //             'adminname'=>$res->org_name,
        //             'sid'=>$sid,
        //             'org_code'=>$res->org_code,
        //             'mchid'=>$deviceUUID

        //         ];
    
        //     }

       // }

        
       $data = [
                    'id'=>$res->id,
                    'adminname'=>$res->org_name,
                    'sid'=>$sid,
                    'org_code'=>$res->org_code,
                    'mchid'=>$deviceUUID

                ];
        
       
                
      
       return response()->json(['code'=>200,'data'=>$data]);



       
    }

    public function logout(Request $request)
    {

        //var_dump($request->all());

        $userId = $request->id;

        $cacheName = 'deviceUUID:user'.$userId; 

       // var_dump(123,$cacheName);exit;

      //   Redis::get($cacheName);
        Redis::del($cacheName);
        //$m = Redis::get($cacheName);
        //var_dump(222222,$m);
        
      
         Session::forget(session_id());
        // Redis::del()
        

        return response()->json(['code'=>200]);
    }

    public function info()
    {
        $where[] = ['org_code','=',ORGCODE];

        $where[] = ['is_del','=',1];

        $res = Orgs::where($where)->first();

        $unread = Message::where([
            ['org_code','=',ORGCODE],
            ['status','=',1],
        ])->count();

        $read = Message::where([
            ['org_code','=',ORGCODE],
            ['status','=',2],
        ])->count();

        //20190603 查找不同消息类型未读数量
        $messages = Message::where([
            ['org_code','=',ORGCODE],
            ['status','=',1],
            ])->distinct()
            ->get(['type_id', 'type_name'])->toArray();
        $messageType = [];
        foreach($messages as $k=>$v){
            $messageType[$k]['type_id'] = $v['type_id'];
            $messageType[$k]['type_name'] = $v['type_name'];
            $messageType[$k]['unread'] = Message::where([
                ['org_code','=',ORGCODE],
                ['status','=',1],
                ['type_id','=',$v['type_id']],
            ])->count();
        }
        $data = [
            'org_info'=>$res,
            'unread'=>$unread,
            'read'=>$read,
            'messageType'=>$messageType,
        ];
        return response()->json(['code'=>200,'msg'=>'success','data'=>$data]);
    }

    public function install(Request $request)
    {
        $openid = $request->input('openId','123123');

        $secret = $request->input('secret','12312312');
        try{
            $org = Orgs::where('org_code',1)->first();
            $org->openId = $openid;
            $org->secret = $secret;
            $res = $org->save();
            if(!$res){
                throw new \Exception('更新openid失败');
            }
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'msg'=>'ok']);
    }
    public function getDeviceUUID($id){
        $num = rand(1,9999);
        $devicecode = md5('xiaoyi'.$id.$num.time());
        return $devicecode;
    }
    public function editpwd(Request $request){

        //var_dump($request->all());
        $data['id']=$request->input('id');
        $data['pwd']=md5($request->input('password'));


        try{
          //  $org = Orgs::where('org_code',1)->first();
           
            $res = Orgs::edit($data);
            if(!$res){
                throw new \Exception('更新失败');
            }
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'msg'=>'ok']);



    }
}
