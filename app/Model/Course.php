<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    protected $table = 'course';
    static public function add($arr)
    {
        try{
            $id = DB::table('course')->insertGetId($arr);
        }catch (\Exception $e){
            return ['code'=>500,'msg'=>$e->getMessage()];
        }
        return ['code'=>200,'id'=>$id];
    }

    static public function search($arr)
    {

       // DB::connection()->enableQueryLog();  // 开启QueryLog


        $data = DB::table('course')->where($arr['where'])->offset($arr['offset'])->limit($arr['limit'])->orderBy('created_at',$arr['order'])->get();

       // var_dump(123,$data);
       
        $count = DB::table('course')->where($arr['where'])->count();
        return ['data'=>$data,'count'=>$count];
    }
}
