<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActivityRecord extends Model
{
    protected $table='activity_record';

    static public function search($arr)
    {
        $data =  DB::table('activity_record')->where($arr['where'])->offset($arr['offset'])->limit($arr['limit'])->get();
        $count = DB::table('activity_record')->where($arr['where'])->count();
        return ['data'=>$data,'count'=>$count];
    }

    static public function add($arr)
    {
        return DB::table('activity_record')->insert($arr);
    }

    static public function edit($arr)
    {
        return DB::table('activity_record')->where('id',$arr['id'])->update($arr);

    }

    static public function del($ids)
    {
        foreach ($ids as $key=>$value)
        {
            $res = DB::table('activity_record')->where('id',$value)->update(['is_del'=>2]);
            if(!$res)
                return $res;
        }

        return $res;
    }

}
