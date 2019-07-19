<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MessageType extends Model
{
    protected $table = 'message_type';
    static public function search($arr)
    {
        $data = DB::table('message_type')->where($arr['where'])->offset($arr['offset'])->limit($arr['limit'])->get();
        $count = DB::table('message_type')->where($arr['where'])->count();

        return ['data'=>$data,'count'=>$count];
    }

    static public function add($arr)
    {
        return DB::table('message_type')->insert($arr);
    }

    static public function edit($arr)
    {
        return DB::table('message_type')->where('id',$arr['id'])->update($arr);
    }

    static public function del($ids)
    {

        foreach ($ids as $key=>$value)
        {
            $res = DB::table('message_type')->where('id',$value)->update(['is_del'=>2]);
            if(!$res)
                return $res;
        }
        return $res;
    }


}
