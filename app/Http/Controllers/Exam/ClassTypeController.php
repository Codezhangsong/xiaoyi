<?php

namespace App\Http\Controllers\Exam;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Model\Exam\ClassType;

class ClassTypeController extends Controller
{
    public function search (Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $page = $request->input('page',1);
        $page = $page - 1;
        $limit = $request->input('limit',10);
        $offset = $limit * $page;
        $where[] = ['is_del','=',1];
        if($id)
            $where[] = ['id','=',$id];
        if($name)
            $where[] = ['name','=',$name];
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
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());

            $result = ClassType::search($arr);
            if($result['count']==0)
                throw new \Exception('暂无数据');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>['data'=>$result['data'],'count'=>$result['count']]]);
    }

    public function add(Request $request)
    {

        $name = $request->input('name');

        $validator = Validator::make($request->all(),[
            'name'=>'required|string',
        ]);
        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $arr = [
                'name'=>$name,
                'updated_at'=>Carbon::now(),
                'created_at'=>Carbon::now(),
            ];
            //判断数据是否已存在
            if(ClassType::single(['name'=>$name])){
                return response()->json(['code'=>500,'msg'=>'分类类型已存在']);
            }
            $res = ClassType::add($arr);
            if(!$res)
                throw new \Exception('插入失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');

        $validator = Validator::make($request->all(),[
            'id'=>'required|integer',
            'name'=>'required|string',
        ]);
        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $arr = [
                'id'=>$id,
                'name'=>$name,
                'updated_at'=>Carbon::now(),
            ];
            //判断数据是否已存在
            if(ClassType::single(['name'=>$name])){
                return response()->json(['code'=>500,'msg'=>'分类类型已存在']);
            }
            $res = ClassType::edit($arr);
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

        $validator = Validator::make($request->all(), [
            'ids'=>'required',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $id_arr = json_decode($ids,true);
            $res = ClassType::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200]);
    }
}
