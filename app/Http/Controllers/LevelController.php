<?php

namespace App\Http\Controllers;

use App\Model\Level;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function search(Request $request)
    {

        $id = $request->input('id');
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $page = $page-1;
        $where = [
            ['is_del','=',1],
            ['is_show','=',1],
        ];
        if($id)
            $where[] = ['id','=',$id];
        if(ORGCODE)
            $where[] = ['org_code','=',ORGCODE];

        $offset = $page*$limit;
        $arr = [
            'where'=>$where,
            'offset'=>$offset,
            'limit'=>$limit,
        ];

        try{
            $result = Level::search($arr);
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
        $levelName = $request->input('levelName');
        $desc = $request->input('desc');

        $validator = Validator::make($request->all(),[
            'levelName'=>'required|string',
            'desc'=>'string',
        ]);
        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $arr = [
                'level_name'=>$levelName,
                'desc'=>$desc,
                'org_code'=>ORGCODE,
                'updated_at'=>Carbon::now(),
                'created_at'=>Carbon::now(),
            ];
            $res = Level::add($arr);
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
        $levelName = $request->input('levelName');
        $desc = $request->input('desc');

        $validator = Validator::make($request->all(),[
            'id'=>'required|integer',
            'levelName'=>'required|string',
            'desc'=>'string',
        ]);
        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $arr = [
                'id'=>$id,
                'level_name'=>$levelName,
                'desc'=>$desc,
                'updated_at'=>Carbon::now(),
                'created_at'=>Carbon::now(),
            ];
            $res = Level::edit($arr);
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
            $res = Level::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200]);
    }

}
