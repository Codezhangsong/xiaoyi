<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Students extends Model
{
    static public function add($arr)
    {
        $res = DB::table('students')->insert($arr);

        return $res;
    }

    static public function edit($arr)
    {

        $res = DB::table('students')->where('id',$arr['id'])->update($arr);

        return $res;
    }

    static public function search($arr)
    {

        $data = DB::table('students')->where($arr['where'])->offset($arr['offset'])->limit($arr['limit'])->get();
        $count = DB::table('students')->where($arr['where'])->count();
        return ['data'=>$data,'count'=>$count];
    }

    static public function export($arr)
    {
        $data = [];
        $count = DB::table('students')->where($arr['where'])->count();
        if($count!=0)
            $data = DB::table('students')->where($arr['where'])->get();

        return ['data'=>$data,'count'=>$count];
    }

    static public function del($ids)
    {
            foreach ($ids as $key=>$value)
            {
                $res = DB::table('students')->where('id',$value)->update(['is_del'=>2]);
                if(!$res)
                    return $res;
            }

        return $res;
    }

}
