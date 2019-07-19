<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActivityClass extends Model
{
    protected $table='activity_class';

    static public function search($arr)
    {
        if(ORGCODE){
            $where = [
                ['is_del','=',1],
                ['org_code','=',ORGCODE],
            ];
        }else{
            $where = [
                ['is_del','=',1],
            ];
        }
        $data = DB::table('activity_class')->where($where)->offset($arr['offset'])->limit($arr['limit'])->get();
        $count = DB::table('activity_class')->where($where)->count();

        return ['data'=>$data,'count'=>$count];
    }

    static public function add($arr)
    {
        return DB::table('activity_class')->insert($arr);
    }

    static public function edit($arr)
    {
        return DB::table('activity_class')->where('id',$arr['id'])->update($arr);
    }

    static public function del($ids)
    {

        foreach ($ids as $key=>$value)
        {
            $res = DB::table('activity_class')->where('id',$value)->update(['is_del'=>2]);
            if(!$res)
                return $res;
        }
        return $res;
    }
}
