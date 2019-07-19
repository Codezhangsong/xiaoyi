<?php

namespace App\Http\Controllers\Exam;


use App\Model\School;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class SchoolController extends Controller
{
    public function search(Request $request)
    {
        $id  = $request->input('id');
        $name  = $request->input('name');
        $region  = $request->input('region');
        $page  = $request->input('page',1);
        $page = $page -1;
        $limit  = $request->input('limit',10);
        $offset = $limit * $page;

        $validator = Validator::make($request->all(),[
            'id' => 'numeric',
            'name' => 'string',
            'region' => 'string',
            'page' => 'numeric',
            'limit' => 'numeric',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());

            $where[] = ['is_del','=',1];
            if($id)
                $where[] = [ 'id','=',$id];
            if($name)
                $where[] = ['name','=',$name];
            if($region){
                $where[] = ['region','=',$region];
            }

            $arr = [
                'where'=>$where,
                'limit'=>$limit,
                'offset'=>$offset,
            ];
            $result = School::search($arr);
            if($result['count']==0)
                throw new \Exception('暂无数据');
            $careerList = [];
            foreach ($result['data']  as $k=>$v){
                $careerList[] = $v->career;
            }
        }catch (\Exception $e){
            return response()->json(['code'=>500,'data'=>'','msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>['data'=>$result['data'],'careerList'=>array_unique($careerList),'count'=>$result['count']],'msg'=>'']);
    }

    public function add(Request $request)
    {
        $name  = $request->input('name');
        $region  = $request->input('region');
        $datetime = Carbon::now();

        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'region' => 'required|string',
            ]);
            if ($validator->fails())
                throw new \Exception($validator->errors());
            //手机号是否已存在
            if(School::single(['name'=>$name,'region'=>$region])){
                return response()->json(['code'=>500,'data'=>'','msg'=>'学校已存在']);
            }
            $arr = [
                'name'=>$name,
                'region'=>$region,
                'reg_date'=>$datetime->format('Y-m-d'),
                'created_at'=>$datetime,
                'updated_at'=>$datetime,
            ];

            $res = School::add($arr);
            if(!$res)
                throw new \Exception('插入失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'data'=>'','msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>'','msg'=>'添加成功']);
    }

    public function edit(Request $request)
    {
        $id  = $request->input('id');
        $name  = $request->input('name');
        $region  = $request->input('region');
        $datetime = Carbon::now();

        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required|string',
                'region' => 'required|string',

            ]);
            if ($validator->fails())
                throw new \Exception($validator->errors());
            $arr = [
                'id'=>$id,
                'name'=>$name,
                'region'=>$region,
                'updated_at'=>$datetime,
            ];
            $res = School::edit($arr);
            if(!$res)
                throw new \Exception('编辑失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'data'=>'','msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>'','msg'=>'修改成功']);
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

            $res = School::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'data'=>'','msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>'','msg'=>'删除成功']);
    }


}
