<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
     protected $table='admin';

    static public function add($arr)
    {
        return DB::table('admin')->insert($arr);
    }

    static public function edit($arr)
    {
        return DB::table('admin')->where('id',$arr['id'])->update($arr);
    }

    static public function del($ids)
    {

        foreach ($ids as $key=>$value)
        {
            $res = DB::table('admin')->where('id',$value)->update(['is_del'=>2]);
            if(!$res)
                return $res;
        }
        return $res;
    }

    static public function search($arr)
    {

        $data = DB::table('admin')->where($arr['where'])->offset($arr['offset'])->limit($arr['limit'])->get();
        $count = DB::table('admin')->where($arr['where'])->count();

        return ['data'=>$data,'count'=>$count];
    }
    static public function list($arr)
    {
      return   DB::table('admin')->where($arr['where'])->get();
       

    }
}
