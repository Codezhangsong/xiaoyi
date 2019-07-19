<?php

namespace App\Http\Controllers\Channel;

use App\Model\Channel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ChannelController extends Controller
{
    public function search(Request $request)
    {
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $id = $request->input('id');
        $page = $page-1;

        $where = [
            ['is_del','=',1],
        ];

        if(ORGCODE)
            $where[] = ['org_code','=',ORGCODE];

        if($id)
            $where[] = ['id','=',$id];

        $offset = $page*$limit;
        $arr = [
            'where'=>$where,
            'offset'=>$offset,
            'limit'=>$limit,
        ];

        try{
            $result = Channel::search($arr);

            if($result['count']==0)
                throw new \Exception('暂无数据');

        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200,'data'=>[
            'data'=>$result['data'],
            'count'=>$result['count']
        ]]);
    }

    public function add(Request $request)
    {
        $channel = $request->input('channel');
        $comment = $request->input('comment');

        $validator = Validator::make($request->all(),[
            'channel'=>'required|string',
            'comment'=>'string',
        ]);
        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $arr = [
                'channel'=>$channel,
                'comment'=>$comment,
                'updated_at'=>Carbon::now(),
                'created_at'=>Carbon::now(),
                'org_code'=>ORGCODE,
            ];
            $res = Channel::add($arr);
            if(!$res)
                throw new \Exception('插入失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');
        $channel = $request->input('channel');
        $comment = $request->input('comment');

        $validator = Validator::make($request->all(),[
            'id'=>'required|integer',
            'channel'=>'required|string',
            'comment'=>'string',
        ]);
        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $arr = [
                'id'=>$id,
                'channel'=>$channel,
                'comment'=>$comment,
                'updated_at'=>Carbon::now(),
                'created_at'=>Carbon::now(),
            ];
            $res = Channel::edit($arr);
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
            $res = Channel::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200]);
    }
}
