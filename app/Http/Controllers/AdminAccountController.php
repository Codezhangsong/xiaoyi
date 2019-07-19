<?php

namespace App\Http\Controllers;

use App\Model\Account;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AdminAccountController extends Controller
{
   
    public function check(Request $request){
        $name = $request->input('uname');
        $password = $request->input('password');
       
        $where[] = ['is_del','=',1];
        if($name)
            $where[] = ['adminname','=',$name];
        if($password)
            $where[] = ['adminpwd','=',md5($password)];
        

        $arr = [
            'where'=>$where
        ];
        try{

            $validator = Validator::make($arr, [
                'name' => 'string',
                'password' => 'string',
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());

                

            $result = Account::list($arr);
          
            if(empty($result[0]))
                throw new \Exception('暂无数据');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }
       // var_dump($result[0]->adminname);
       $data['id']=$result[0]->id;
       $data['adminname']=$result[0]->adminname;
        Session::put(session_id());
        Session::put('org_code',null);
        Session::put('adminname',$result[0]->adminname);
        Session::save();

        return response()->json(['code'=>200,'data'=>$data]);

    }

    public function logout()
    {
        Session::forget(session_id());

        return response()->json(['code'=>200]);
    }
    public function add (Request $request)
    {
        
        $name = $request->input('uname');
        $pwd = $request->input('password');
        $time = time();
       

        $arr = [
            'adminname'=>$name,
            'adminpwd'=>md5($pwd),
            'status'=>1,
            'add_time'=>date('Y-m-d h:m:s',time())
        ];

        

        try{
            $validator = Validator::make($arr, [
                'adminname' => 'required|string'
            ]);
            if($validator->fails())
                throw new \Exception($validator->errors());
            $result = Account::add($arr);
            if(!$result)
                throw new \Exception('新增失败');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }
        Session::put(session_id(),'');
        return response()->json(['code'=>200]);
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


    public function search (Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');

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
}
