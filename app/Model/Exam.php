<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
class Exam extends Model
{
    static public function search($arr)
    {
        $data = DB::table('exam_user')->where($arr['where'])->offset($arr['offset'])->limit($arr['limit'])->orderBy('created_at','desc')->get();
        $count = DB::table('exam_user')->where($arr['where'])->count();

        return ['data'=>$data,'count'=>$count];
    }
    static public function single($where)
    {

        $data = DB::table('exam_user')->where($where)->first();

        return $data;
    }
    static public function add($arr)
    {
        $res = DB::table('exam_user')->insert($arr);

        return $res;
    }

    static public function edit($arr)
    {

        $res = DB::table('exam_user')->where('mobile', $arr['mobile'])->update($arr);

        return $res;
    }

    static public function del($ids)
    {
        foreach ($ids as $key=>$value)
        {
            $res = DB::table('exam_user')->where('id',$value)->update(['is_del'=>2]);
            if(!$res)
                return $res;
        }

        return $res;
    }
    static public function export($arr)
    {
        $data = [];
        $count = DB::table('exam_user')->where($arr['where'])->count();
        if($count!=0)
            $data = DB::table('exam_user')->where($arr['where'])->get();

        return ['data'=>$data,'count'=>$count];
    }
}
