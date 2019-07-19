<?php

namespace App\Http\Controllers\Exam;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Model\Exam\AgeRange;

class AgeRangeController extends Controller
{
    public function search (Request $request)
    {
        $id = $request->input('id');
        $ageRange = $request->input('ageRange');
        $page = $request->input('page',1);
        $page = $page - 1;
        $limit = $request->input('limit',10);
        $offset = $limit * $page;
        $where[] = ['is_del','=',1];
        if($id)
            $where[] = ['id','=',$id];
        if($ageRange)
            $where[] = ['age_range','=',$ageRange];
        $arr = [
            'id'=>$id,
            'limit'=>$limit,
            'offset'=>$offset,
            'where'=>$where
        ];
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'integer',
                'ageRange' => 'string',
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());

            $result = AgeRange::search($arr);
            if($result['count']==0)
                throw new \Exception('暂无数据');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>['data'=>$result['data'],'count'=>$result['count']]]);
    }

    public function add(Request $request)
    {

        $ageRange = $request->input('ageRange');

        $validator = Validator::make($request->all(),[
            'ageRange'=>'required|string',
        ]);
        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $arr = [
                'age_range'=>$ageRange,
                'updated_at'=>Carbon::now(),
                'created_at'=>Carbon::now(),
            ];
            //判断数据是否已存在
            if(AgeRange::single(['age_range'=>$ageRange])){
                return response()->json(['code'=>500,'msg'=>'数据已存在']);
            }
            $res = AgeRange::add($arr);
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
        $ageRange = $request->input('ageRange');

        $validator = Validator::make($request->all(),[
            'id'=>'required|integer',
            'ageRange'=>'required|string',
        ]);
        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $arr = [
                'id'=>$id,
                'age_range'=>$ageRange,
                'updated_at'=>Carbon::now(),
            ];
            //判断数据是否已存在
            if(AgeRange::single(['age_range'=>$ageRange])){
                return response()->json(['code'=>500,'msg'=>'数据已存在']);
            }
            $res = AgeRange::edit($arr);
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
            $res = AgeRange::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200]);
    }
}
