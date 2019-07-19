<?php

namespace App\Http\Controllers;


use App\Model\Students;
use App\Services\Utils\BaiduMap;
use App\Services\Utils\Helps;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function search(Request $request)
    {
        $id  = $request->input('id');
        $intention  = $request->input('intention');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $page  = $request->input('page',1);
        $page = $page -1;
        $limit  = $request->input('limit',10);
        $offset = $limit * $page;

        $validator = Validator::make($request->all(),[
            'id' => 'numeric',
            'intention' => 'string',
            'startDate' => 'date',
            'endDate' => 'date',
            'page' => 'numeric',
            'limit' => 'numeric',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());

            $where[] = ['is_del','=',1];
            if($id)
                $where[] = [ 'id','=',$id];
            if($intention)
                $where[] = ['intention','=',$intention];
            if($startDate && $endDate){
                $where[] = ['reg_date','>=',$startDate];
                $where[] = ['reg_date','<=',$endDate];
            }

            if(ORGCODE)
                $where[] = [ 'org_code','=',ORGCODE];

            $arr = [
                'where'=>$where,
                'limit'=>$limit,
                'offset'=>$offset,
            ];
            $result = Students::search($arr);
            if($result['count']==0)
                throw new \Exception('暂无数据');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>['data'=>$result['data'],'count'=>$result['count']]]);
    }

    public function add(Request $request)
    {
        $name  = $request->input('name');
        $parentName  = $request->input('parentName');
        $parentMobile  = $request->input('parentMobile');
        $gender  = $request->input('gender');
        $school  = $request->input('school');
        $origin  = $request->input('origin');
        $intention  = $request->input('intention');
        $birthday  = $request->input('birthday');
        $province  = $request->input('province');
        $city  = $request->input('city');
        $region  = $request->input('region');
        $street  = $request->input('street');
        $datetime = Carbon::now();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'parentName' => 'required|string',
            'parentMobile' => 'required|digits:11',
            'gender' => 'required|digits:1',
            'school' => 'required|string',
            'origin' => 'required|string',
            'intention' => 'required|string',
            'birthday' => 'required|date',
            'province' => 'required|string',
            'city' => 'required|string',
            'region' => 'required|string',
            'street' => 'string',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $age = Helps::birthdayTransLate($birthday);
            $arr = [
                'name'=>$name,
                'parent_name'=>$parentName,
                'parent_mobile'=>$parentMobile,
                'gender'=>$gender,
                'birthday'=>$birthday,
                'age'=>$age,
                'school'=>$school,
                'origin'=>$origin,
                'intention'=>$intention,
                'created_at'=>$datetime,
                'reg_date'=>$datetime->format('Y-m-d'),
                'updated_at'=>$datetime,
                'province'=>$province,
                'city'=>$city,
                'region'=>$region,
                'street'=>$street,
                'org_code'=>ORGCODE,
            ];

            $location = BaiduMap::search($school, $city);
            if(!empty($location)){
                $arr['lat'] = $location['lat'];
                $arr['lng'] = $location['lng'];
            }
            $res = Students::add($arr);
            if(!$res)
                throw new \Exception('insert fail');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200]);
    }

    public function edit(Request $request)
    {
        $datetime = Carbon::now();
        $id  = $request->input('id');
        $name  = $request->input('name');
        $parentName  = $request->input('parentName');
        $parentMobile  = $request->input('parentMobile');
        $gender  = $request->input('gender');
        $birthday  = $request->input('birthday');
        $school  = $request->input('school');
        $origin  = $request->input('origin');
        $intention  = $request->input('intention');
        $province  = $request->input('province');
        $city  = $request->input('city');
        $region  = $request->input('region');
        $street  = $request->input('street');

        $validator = Validator::make($request->all(), [
            'id'=>'required|numeric',
            'name' => 'required|string',
            'parentName' => 'required|string',
            'parentMobile' => 'required|digits:11',
            'gender' => 'required|digits:1',
            'school' => 'required|string',
            'origin' => 'required|string',
            'intention' => 'required|string',
            'birthday' => 'required|date',
            'province' => 'required|string',
            'city' => 'required|string',
            'region' => 'required|string',
            'street' => 'required|string',
        ]);

        $age = Helps::birthdayTransLate($birthday);

        $arr = [
            'id'=>$id,
            'name'=>$name,
            'parent_name'=>$parentName,
            'parent_mobile'=>$parentMobile,
            'province'=>$province,
            'city'=>$city,
            'region'=>$region,
            'street'=>$street,
            'gender'=>$gender,
            'birthday'=>$birthday,
            'age'=>$age,
            'school'=>$school,
            'origin'=>$origin,
            'intention'=>$intention,
            'updated_at'=>$datetime,
        ];
        try{
            if ($validator->fails())
                throw new \Exception($validator->errors());

            $location = BaiduMap::search($street, $city);
            if(!empty($location)){
                $arr['lat'] = $location['lat'];
                $arr['lng'] = $location['lng'];
            }
            $res = Students::edit($arr);
            if(!$res)
                throw new \Exception('edit fail');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
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
            $res = Students::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200]);
    }


}
