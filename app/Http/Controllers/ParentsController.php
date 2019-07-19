<?php

namespace App\Http\Controllers;

use App\Model\Parents;
use App\Services\Utils\BaiduMap;
use App\Services\Utils\GaoDeMap;
use App\Services\Utils\Helps;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParentsController extends Controller
{
    public function search(Request $request)
    {
        $id = $request->input('id');
        $levelId = $request->input('levelId');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $page  = $request->input('page',1);
        $limit  = $request->input('limit',10);
        $page = $page - 1;
        $offset = $limit * $page;

        $validator = Validator::make($request->all(),[
            'id' => 'numeric',
            'levelId' => 'numeric',
            'startDate' => 'date',
            'endDate' => 'date',
            'page' => 'numeric',
            'limit' => 'numeric',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $where[] = ['is_del','=',1];
            $where[] = ['use_flag','=',1];
            if(ORGCODE)
                $where[] = ['org_code','=',ORGCODE];

            if($id)
                $where[] = ['id','=',$id];

            if($levelId)
                $where[] = ['level_id','=',$levelId];

            if($startDate && $endDate){
                $where[] = ['reg_date','>=',$startDate];
                $where[] = ['reg_date','<=',$endDate];
            }

            $arr = [
              'where'=>$where,
              'limit'=>$limit,
              'offset'=>$offset
            ];
            $res = Parents::search($arr);
            if($res['count']==0)
                throw new \Exception('暂无数据');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>['data'=>$res['data'],'count'=>$res['count']]]);
    }

    public function add(Request $request)
    {
        $name  = $request->input('name');
        $password  = $request->input('password');
        $mobile  = $request->input('mobile');
        $birthday  = $request->input('birthday');
        $gender  = $request->input('gender');
        $levelId = $request->input('levelId');
        $level  = $request->input('level');
        $province  = $request->input('province');
        $city  = $request->input('city');
        $region  = $request->input('region');
        $street  = $request->input('street');
        $occupation  = $request->input('occupation');
        $tagId  = $request->input('tagId');
        $tagName  = $request->input('tagName');
        $useFlg = $request->input('useFlag',1);
        $datetime = Carbon::now();

        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'password' => 'required|string',
                'mobile' => 'required|digits:11',
                'birthday' => 'required|date',
                'gender' => 'required|digits:1',
                'levelId' => 'required',
                'level' => 'required|string',
                'province' => 'required|string',
                'city' => 'required|string',
                'region' => 'required|string',
                'street' => 'required|string',
                'occupation' => 'required|string',
                'tagId' => 'required|string',
                'tagName' => 'required|string',
            ]);
            if ($validator->fails())
                throw new \Exception($validator->errors());

            $age = Helps::birthdayTransLate($birthday);
            $arr = [
                'name'=>$name,
                'password'=>md5($password),
                'mobile'=>$mobile,
                'gender'=>$gender,
                'birthday'=>$birthday,
                'age'=>$age,
                'level'=>$level,
                'level_id'=>$levelId,
                'province'=>$province,
                'city'=>$city,
                'region'=>$region,
                'street'=>$street,
                'occupation'=>$occupation,
                'tag_id'=>$tagId,
                'tag_name'=>$tagName,
                'use_flag'=>$useFlg,
                'reg_date'=>$datetime->format('Y-m-d'),
                'created_at'=>$datetime,
                'updated_at'=>$datetime,
                'org_code'=>ORGCODE,
            ];
            $address = [
                'address'=>$street,
                'city'=>$city,
            ];
            $location = GaoDeMap::search($address);
            if(!empty($location)){
                $arr['lat'] = $location['lat'];
                $arr['lng'] = $location['lng'];
            }

            $res = Parents::add($arr);
            if(!$res)
                throw new \Exception('插入失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }

    public function edit(Request $request)
    {
        $id  = $request->input('id');
        $name  = $request->input('name');
        $password  = $request->input('password');
        $mobile  = $request->input('mobile');
        $birthday  = $request->input('birthday');
        $gender  = $request->input('gender');
        $levelId = $request->input('levelId');
        $level  = $request->input('level');
        $city  = $request->input('city');
        $province  = $request->input('province');
        $region  = $request->input('region');
        $street  = $request->input('street');
        $occupation  = $request->input('occupation');
        $tagId  = $request->input('tagId');
        $tagName  = $request->input('tagName');
        $useFlg = $request->input('useFlag');
        $datetime = Carbon::now();

        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required|string',
                'password' => 'required|string',
                'mobile' => 'required|digits:11',
                'birthday' => 'required|date',
                'gender' => 'required|digits:1',
                'levelId' => 'required',
                'level' => 'required|string',
                'province' => 'required|string',
                'city' => 'required|string',
                'region' => 'required|string',
                'street' => 'required|string',
                'occupation' => 'required|string',
                'tagId' => 'required|string',
                'tagName' => 'required|string',

            ]);
            if ($validator->fails())
                throw new \Exception($validator->errors());
            $age = Helps::birthdayTransLate($birthday);
            $arr = [
                'id'=>$id,
                'name'=>$name,
                'password'=>md5($password),
                'mobile'=>$mobile,
                'gender'=>$gender,
                'birthday'=>$birthday,
                'age'=>$age,
                'level'=>$level,
                'level_id'=>$levelId,
                'province'=>$province,
                'city'=>$city,
                'region'=>$region,
                'street'=>$street,
                'occupation'=>$occupation,
                'tag_id'=>$tagId,
                'tag_name'=>$tagName,
                'use_flag'=>$useFlg,
                'updated_at'=>$datetime,
            ];
            $location = BaiduMap::search($street, $city);
            if(!empty($location)){
                $arr['lat'] = $location['lat'];
                $arr['lng'] = $location['lng'];
            }
            $res = Parents::edit($arr);
            if(!$res)
                throw new \Exception('编辑失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

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

            $res = Parents::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }

}
