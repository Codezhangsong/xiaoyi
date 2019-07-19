<?php

namespace App\Http\Controllers;

use App\Model\ActivityClass;
use Illuminate\Http\Request;

class ActivityClassController extends Controller
{
    public function search (Request $request)
    {
        $page = $request->input('page',1);
        $page = $page - 1;
        $limit = $request->input('limit',10);
        $offset = $page * $limit;
        $arr = [
            'limit'=>$limit,
            'offset'=>$offset,
        ];
        try{
            $result = ActivityClass::search($arr);
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
        $typeName = $request->input('typeName');
        $arr = [
            'id'=>$id,
            'type_name'=>$typeName,
            'updated_at'=>Carbon::now(),
        ];

        try{
            $result = ActivityClass::edit($arr);
            $validator = Validator::make($request->all(), [
                'typeName' => 'required|string',
                'id' => 'required|integer',
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());
            if(!$result)
                throw new \Exception('编辑失败');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }

    public function add (Request $request)
    {
        $typeName = $request->input('typeName');
        $creator = $request->input('creator');

        $arr = [
            'type_name'=>$typeName,
            'creator'=>$creator,
            'updated_at'=>Carbon::now(),
            'created_at'=>Carbon::now(),
            'org_code'=>ORGCODE,
        ];

        try{
            $validator = Validator::make($request->all(), [
                'typeName' => 'required|string',
                'creator' => 'required|string',
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());
            $result = ActivityClass::add($arr);
            if(!$result)
                throw new \Exception('新增失败');
        }catch(\Exception $e){
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

            if($validator->fails())
                throw new \Exception($validator->errors());

            $id_arr = json_decode($ids,true);



            $res = ActivityClass::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }
}
