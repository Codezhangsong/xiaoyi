<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class Parents extends Model
{
    static public function add($arr)
    {
        try{
            DB::table('parents')->insert($arr);
        }catch (\Exception $e)
        {
            return ['code'=>500,'msg'=>$e->getMessage()];
        }
        return ['code'=>200];
    }

    static public function edit($arr)
    {
        $res = DB::table('parents')->where('id',$arr['id'])->update($arr);
        return $res;
    }


    static public function search($arr)
    {
        $data =  DB::table('parents')->where($arr['where'])->offset($arr['offset'])->limit($arr['limit'])
            ->orderBy('reg_date','desc')->get();

        $count = DB::table('parents')->where($arr['where'])->count();

        return ['data'=>$data,'count'=>$count];
    }

    static public function export($arr)
    {
        $data = [];
        $count = DB::table('parents')->where($arr['where'])->count();
        if($count!=0)
            $data = DB::table('parents')->where($arr['where'])->get();

        return ['data'=>$data,'count'=>$count];
    }

    static public function del($ids)
    {

            foreach ($ids as $key=>$value)
            {
                $res = DB::table('parents')->where('id',$value)->update(['is_del'=>2]);
                if(!$res)
                    return $res;
            }
        return $res;
    }

}
