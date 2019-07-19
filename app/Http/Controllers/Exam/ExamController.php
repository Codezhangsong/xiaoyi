<?php

namespace App\Http\Controllers\Exam;


use App\Model\Exam;
use App\Model\School;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Services\ChuanglanSmsApi;

class ExamController extends Controller
{
    public function search(Request $request)
    {
        $id  = $request->input('id');
        $schoolId  = $request->input('school_id');
        $career  = $request->input('career');
        $school  = $request->input('school');
        $classId  = $request->input('class_id');
        $class  = $request->input('class');
        $name  = $request->input('name');
        $mobile  = $request->input('mobile');
        $password = $request->input('password');
        $page  = $request->input('page',1);
        $page = $page -1;
        $limit  = $request->input('limit',10);
        $offset = $limit * $page;

        $validator = Validator::make($request->all(),[
            'id' => 'numeric',
            'school' => 'string',
            'career' => 'string',
            'class' => 'string',
            'name' => 'string',
            'mobile' => 'digits:11',
            'password' => 'string',
            'page' => 'numeric',
            'limit' => 'numeric',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());

            $where[] = ['is_del','=',1];
            if($id)
                $where[] = [ 'id','=',$id];
            if($school)
                $where[] = ['school','=',$school];
           if($schoolId)
                $where[] = ['school_id','=',$schoolId];
            if($class)
                 $where[] = ['class','=',$class];
            if($classId)
                $where[] = ['class_id','=',$classId];
            if($name)
                $where[] = ['name','=',$name];
            if($password){
                $where[] = ['password','=',md5($password)];
            }
            if($mobile){
                $where[] = ['mobile','=',$mobile];
            }
            if($career){
                $where[] = ['career','=',$career];
            }

            $arr = [
                'where'=>$where,
                'limit'=>$limit,
                'offset'=>$offset,
            ];
            $result = Exam::search($arr);
            if($result['count']==0)
                throw new \Exception('暂无数据');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'data'=>'','msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>['data'=>$result['data'],'count'=>$result['count']],'msg'=>'']);
    }
    //登录
    public function login(Request $request)
    {
        $name  = $request->input('name');
        $mobile  = $request->input('mobile');
        $password = $request->input('password');

        $validator = Validator::make($request->all(),[
            'mobile' => 'required|digits:11',
            'password' => 'required|string',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());

            $where['is_del'] = 1;
            if($name)
                $where['name'] = $name;
            if($mobile){
                $where['mobile'] = $mobile;
            }
            $result = Exam::single($where);
            if(!$result)
                return response()->json(['code'=>500,'data'=>'','msg'=>'请先报名']);
            if(md5($password) !== $result->password){
                return response()->json(['code'=>500,'data'=>'','msg'=>'密码输入错误，请稍后重试']);
            }
        }catch (\Exception $e){
            return response()->json(['code'=>500,'data'=>'','msg'=>$e->getMessage()]);
        }
        $userData = (array)$result;
        session(['session_uid'=>$userData['id']]);
        return response()->json(['code'=>200,'data'=>$userData,'msg'=>'登录成功']);
    }
    //报名
    public function add(Request $request)
    {
        $name  = $request->input('name');
        $password  = $request->input('password');
        $mobile  = $request->input('mobile');
        $code  = $request->input('code');
        $schoolId = $request->input('schoolId');
        $school  = $request->input('school');
        $classId = $request->input('classId');
        $class  = $request->input('class');
        $datetime = Carbon::now();

        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'password' => 'required|string',
                'mobile' => 'required|digits:11',
                'schoolId' => 'required',
                'school' => 'required|string',
                'classId' => 'required',
                'class' => 'required|string',
            ]);
            if ($validator->fails())
                throw new \Exception($validator->errors());
          /*  if(session('mobile') !== $mobile){
               return response()->json(['code'=>500,'data'=>['smobile'=>session('mobile'),'mobile'=>$mobile],'msg'=>'请输入验证的手机号']);
           }
           if(session('code') !== intval($code)){
               return response()->json(['code'=>500,'data'=>'','msg'=>'验证码错误']);
           }*/
            //手机号是否已存在
            if(Exam::single(['mobile'=>$mobile])){
                return response()->json(['code'=>500,'data'=>'','msg'=>'手机号已存在']);
            }
            $career = $this->getCareer($schoolId);
            $arr = [
                'name'=>$name,
                'password'=>md5($password),
                'mobile'=>$mobile,
                'school'=>$school,
                'school_id'=>$schoolId,
                'career'=>$career,
                'class'=>$class,
                'class_id'=>$classId,
                'reg_date'=>$datetime->format('Y-m-d'),
                'created_at'=>$datetime,
                'updated_at'=>$datetime,
            ];
            $res = Exam::add($arr);
            if(!$res)
                return response()->json(['code'=>500,'data'=>'','msg'=>'添加失败']);
        }catch (\Exception $e){
            return response()->json(['code'=>500,'data'=>'','msg'=>$e->getMessage()]);
        }
        session(['mobile'=>null]);
        session(['code'=>null]);
        return response()->json(['code'=>200,'data'=>'','msg'=>'添加成功']);
    }
    //忘记密码
    public function edit(Request $request)
    {
        $mobile  = $request->input('mobile');
        $code  = $request->input('code');
        $password  = $request->input('password');
        $qpassword  = $request->input('qpassword');
        $datetime = Carbon::now();
        try{
            $validator = Validator::make($request->all(), [
                'mobile' => 'required',
                'password' => 'required|string',
                'qpassword' => 'required|string',
            ]);
            if ($validator->fails())
                throw new \Exception($validator->errors());
           /* if(session('mobile') !== $mobile){
                return response()->json(['code'=>500,'data'=>'','msg'=>'请输入验证的手机号']);
            }
            if(session('code') !== intval($code)){
                return response()->json(['code'=>500,'data'=>'','msg'=>'验证码错误']);
            }*/
            if($password !== $qpassword){
                return response()->json(['code'=>500,'data'=>'','msg'=>'两次密码输入不一致']);
            }
            $arr = [
                'mobile'=>$mobile,
                'password'=>md5($password),
                'updated_at'=>$datetime,
            ];
            $res = Exam::edit($arr);
            if(!$res)
                return response()->json(['code'=>500,'data'=>'','msg'=>'重置失败']);
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }
        session(['mobile'=>null]);
        session(['code'=>null]);
        return response()->json(['code'=>200,'data'=>'','msg'=>'重置成功']);
    }
    public function editAdmin(Request $request)
    {
        $name  = $request->input('name');
        $mobile  = $request->input('mobile');
        $schoolId  = $request->input('schoolId');
        $school  = $request->input('school');
        $classId  = $request->input('classId');
        $class  = $request->input('class');
        $password  = $request->input('password');
        $datetime = Carbon::now();
        try{
            $validator = Validator::make($request->all(), [
                'mobile' => 'required',
                'name' => 'required|string',
                'school' => 'required|string',
                'class' => 'required|string',
//                'password' => 'required|string',
            ]);
            if ($validator->fails())
                throw new \Exception($validator->errors());
            $career = $this->getCareer($schoolId);
            $arr = [
                'mobile'=>$mobile,
                'name'=>$name,
                'school_id'=>$schoolId,
                'school'=>$school,
                'career'=>$career,
                'class_id'=>$classId,
                'class'=>$class,
                'updated_at'=>$datetime,
            ];
            if(!empty($password)){
                $arr['password'] = md5($password);
            }
            $res = Exam::edit($arr);
            if(!$res)
                return response()->json(['code'=>500,'data'=>'','msg'=>'重置失败']);
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200,'data'=>'','msg'=>'重置成功']);
    }
    //删除
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

            $res = Parents::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }
    //验证手机号
    public function sms(Request $request)
    {
        $type = $request->input('type',1); //1报名2忘记密码
        $mobile=$request->input('mobile');
        if(!$mobile){
            return response()->json(['code'=>500,'data'=>'','msg'=>'手机号不能为空']);
        }
        if($type == 1){
            //手机号是否已存在
            if(Exam::single(['mobile'=>$mobile])){
                return response()->json(['code'=>500,'data'=>'','msg'=>'手机号已存在']);
            }
        }else{
            if(!Exam::single(['mobile'=>$mobile])){
                return response()->json(['code'=>500,'data'=>'','msg'=>'账号不存在']);
            }
        }
        $clapi = new ChuanglanSmsApi();
        $code = mt_rand(100000, 999999);
        /*Session::put('mobile',$mobile);
        Session::put('code',$code);
        return response()->json(['code'=>200,'data'=>['code'=>session('code'),'mobile'=>session('mobile')],'msg'=>"短信发送发送成功"]);*/
       // return;
        //设置您要发送的内容：其中“【】”中括号为运营商签名符号，多签名内容前置添加提交
        $result = $clapi->sendSMS($mobile, '【添翼申学】您好，您的验证码'.$code);

        if (!is_null(json_decode($result))) {

            $output = json_decode($result, true);

            if (isset($output['code']) && $output['code'] == '0') {
                Session::put('mobile',$mobile);
                Session::put('code',$code);
                return response()->json(['code'=>200,'data'=>'','msg'=>"短信发送发送成功"]);
            } else {
                return response()->json(['code'=>500,'data'=>'','msg'=>"短信发送失败：".$output['errorMsg']]);
            }
        } else {
            return response()->json(['code'=>500,'data'=>'','msg'=>$result]);

        }
    }
    //获取学校对应的部门
    public function getCareer($schoolId){
        $where['id'] = $schoolId;
        $result = School::single($where);
        return $result->career;
    }


}
