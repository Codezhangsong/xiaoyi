<?php

namespace App\Http\Controllers;

use App\Model\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function search (Request $request)
    {
        $id = $request->input('id');
        $page = $request->input('page',1);
        $page = $page - 1;
        $limit = $request->input('limit',10);
        $typeId = $request->input('typeId');
        $typeName = $request->input('typeName');
        $creator = $request->input('creator');

        $offset = $page * $limit;
        $where = [];
        $where[] = ['is_del','=',1];
        if($id)
            $where[] = ['id','=',$id];
        if($typeId)
            $where[] = ['type_id','=',$typeId];
        if($typeName)
            $where[] = ['type_name','=',$typeName];
        if($creator)
            $where[] = ['creator','=',$creator];
        // if(ORGCODE)
        //     $where[] = ['org_code','=',ORGCODE];
        $arr = [
            'limit'=>$limit,
            'offset'=>$offset,
            'where'=>$where
        ];
        try{
            $result = Message::search($arr);
            if($result['count']==0)
                throw new \Exception('暂无数据');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>['data'=>$result['data'],'count'=>$result['count']]]);
    }

    public function edit (Request $request)
    {
        $id = $request->input('id',1);
        $title = $request->input('title');
        $typeId = $request->input('typeId');
        $type = $request->input('type');
        $status = $request->input('status');
        $arr = [
          'id'=>$id,
          'title'=>$title,
          'type_id'=>$typeId,
          'type_name'=>$type,
            'status'=>$status,
            'updated_at'=>Carbon::now(),
        ];
        try{
            $result = Message::edit($arr);
            if(!$result)
                throw new \Exception('编辑失败');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }

    public function add (Request $request)
    {
        $typeId = $request->input('typeId');
        $type = $request->input('type');
        $title = $request->input('title');
        $status = $request->input('status',1);

        $arr = [
            'type_id'=>$typeId,
            'type_name'=>$type,
            'title'=>$title,
            'org_code'=>ORGCODE,
            'status'=>$status,
            'updated_at'=>Carbon::now(),
            'created_at'=>Carbon::now(),
        ];

        try{
            $result = Message::add($arr);
            if(!$result)
                throw new \Exception('编辑失败');
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

            $res = Message::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }

}
