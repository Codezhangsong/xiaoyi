<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
class Orgs extends Model
{
    static public function search($arr)
    {
        $data = DB::table('orgs')->where($arr['where'])->offset($arr['offset'])->limit($arr['limit'])->orderBy('created_at','desc')->get();
        $count = DB::table('orgs')->where($arr['where'])->count();

        return ['data'=>$data,'count'=>$count];
    }

    static public function add($arr)
    {
        $res = DB::table('orgs')->insert($arr);

        return $res;
    }

    static public function edit($arr)
    {

        $res = DB::table('orgs')->where('id', $arr['id'])->update($arr);

        return $res;
    }

    static public function del($ids)
    {
        foreach ($ids as $key=>$value)
        {
            $res = DB::table('orgs')->where('id',$value)->update(['is_del'=>2]);
            if(!$res)
                return $res;
        }

        return $res;
    }

    static public function export($arr)
    {
        $data = [];
        $count = DB::table('orgs')->where($arr['where'])->count();
        if($count!=0)
            $data = DB::table('orgs')->where($arr['where'])->get();

        return ['data'=>$data,'count'=>$count];
    }

    static public function check($arr)
    {
      //  DB::connection()->enableQueryLog();
        $res = DB::table('orgs')->where($arr['where'])->first();
        // $log = DB::getQueryLog();
        // var_dump($log);
         return $res;
    }
}
