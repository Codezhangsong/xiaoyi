<?php

namespace App\Http\Controllers;


use App\Model\MessageType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageTypeController extends Controller
{
    public function search (Request $request)
    {
        $id = $request->input('id');
        $page = $request->input('page',1);
        $page = $page - 1;
        $limit = $request->input('limit',10);
        $offset = $page * $limit;
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $creator = $request->input('creator');


        $where = [];
        $where[] = ['is_del','=',1];
        // if(ORGCODE)
        //     $where[] = ['org_code','=',ORGCODE];
        if($id)
            $where[] = ['id','=',$id];
        if($creator)
            $where[] = ['creator','=',$creator];

        if($startDate && $endDate)
        {
            $where[] = ['created_at','>=',$startDate];
            $where[] = ['created_at','<=',$endDate];
        }

        $arr = [
            'limit'=>$limit,
            'offset'=>$offset,
            'where'=>$where
        ];
        try{
            $result = MessageType::search($arr);
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
        $creator = $request->input('creator');
        $arr = [
            'id'=>$id,
            'type_name'=>$typeName,
            'creator'=>$creator,
            'updated_at'=>Carbon::now(),
        ];

        try{
            $result = MessageType::edit($arr);
            $validator = Validator::make($request->all(), [
                'typeName' => 'required|string',
                'creator' => 'required|string',
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
            'org_code'=>ORGCODE,
            'updated_at'=>Carbon::now(),
            'created_at'=>Carbon::now(),
        ];

        try{
            $validator = Validator::make($request->all(), [
                'typeName' => 'required|string',
                'creator' => 'required|string',
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());
            $result = MessageType::add($arr);
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



            $res = MessageType::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }
}
