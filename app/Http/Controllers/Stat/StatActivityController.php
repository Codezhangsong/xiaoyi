<?php

namespace App\Http\Controllers\Stat;

use App\Model\Activity;
use App\Model\StatActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StatActivityController extends Controller
{
    public function index()
    {
        try{
            $where = [];
            $where[] = ['stat_date','=',Carbon::today()];
            $where[] = ['org_code','=',ORGCODE];
            $res = StatActivity::where($where)->orderBy('stat_date','desc')->first();
            if(!$res)
                throw new \Exception('暂无数据');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'err'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200,'data'=>$res]);
    }

    public function trend(Request $request)
    {
        $date_type = $request->input('dateType',1);
        $startDate = $request->input('startDate',Carbon::now()->subMonth(7));
        $endDate = $request->input('endDate',Carbon::now());

        $res = [];
        switch ($date_type)
        {
            case 1:

                $where = [
                    ['created_at','>=', Carbon::today()],
                    ['created_at','<=', Carbon::tomorrow()],
                ];
                if(ORGCODE)
                    $where[] = ['org_code','=',ORGCODE];
            $select = " 
                    count(id) as total_number,
                    
                    SUM(PV) as total_pv,
                    
                    SUM(UV) as total_uv,
                    
                    SUM(stay_minutes) as total_minutes,
                    
                    bounce_rate,
                    
                    hour(created_at)  div 3,
                    
                    CONCAT ( 
                              date_format(created_at,'%Y-%m-%d '),
                              if(hour(created_at) div 3 * 3 <10,  CONCAT('0',hour(created_at) div 3 * 3)     ,  hour(created_at) div 3 * 3),
                              ':00:00'
                              
                            )  as created_at ";

            $res = DB::table('activity')->select(DB::raw($select))->where($where)->groupBy(DB::raw("hour(created_at)  div 3"))->orderBy('created_at','asc')->get();

            if($res->count())
            {
                foreach ($res as $key =>&$value)
                {
                    $last_year_start_date = Carbon::parse($value->created_at)->subYear(1);
                    $last_year_end_date = Carbon::parse($value->created_at)->subYear(1)->subHours(-3);

                    $where = [
                        ['created_at','>=',$last_year_start_date],
                        ['created_at','<=',$last_year_end_date],
                    ];
                    if(ORGCODE)
                        $where[] = ['org_code','=',ORGCODE];
                    $last_year_res = DB::table('activity')->select(DB::raw('COUNT(id) as last_year_num,
                    SUM(PV) as last_year_PV,
                    SUM(UV) as last_year_UV,
                    SUM(stay_minutes) as last_year_stay_minutes
                    '))->where($where)->first();

                    if($last_year_res->last_year_num ==0){
                        $value->last_year_num = 0;
                        $value->last_year_PV = 0;
                        $value->last_year_UV = 0;
                        $value->last_year_stay_minutes = 0;
                    }else{
                        $value->last_year_num = $last_year_res->last_year_num;
                        $value->last_year_PV = $last_year_res->last_year_PV;
                        $value->last_year_UV = $last_year_res->last_year_UV;
                        $value->last_year_stay_minutes = $last_year_res->last_year_stay_minutes;
                    }

                }
            }

            break;

            case 2:

                $where = [
                    ['created_at','>=', Carbon::yesterday()],
                    ['created_at','<=', Carbon::today()],
                ];
                if(ORGCODE)
                    $where[] = ['org_code','=',ORGCODE];
                $select = " 
                    count(id) as total_number,
                    
                    SUM(PV) as total_pv,
                    
                    SUM(UV) as total_uv,
                    
                    SUM(stay_minutes) as total_minutes,
                    
                    bounce_rate,
                    
                    hour(created_at)  div 3,
                    
                    CONCAT ( 
                              date_format(created_at,'%Y-%m-%d '),
                              if(hour(created_at) div 3 * 3 <10,  CONCAT('0',hour(created_at) div 3 * 3)     ,  hour(created_at) div 3 * 3),
                              ':00:00'
                              
                            )  as created_at ";

                $res = DB::table('activity')->select(DB::raw($select))->where($where)->groupBy(DB::raw("hour(created_at)  div 3"))->orderBy('created_at','asc')->get();

                if($res->count())
                {
                    foreach ($res as $key =>&$value)
                    {
                        $last_year_start_date = Carbon::parse($value->created_at)->subYear(1);
                        $last_year_end_date = Carbon::parse($value->created_at)->subYear(1)->subHours(-3);

                        $where = [
                            ['created_at','>=',$last_year_start_date],
                            ['created_at','<=',$last_year_end_date],
                        ];
                        if(ORGCODE)
                            $where[] = ['org_code','=',ORGCODE];
                        $last_year_res = DB::table('activity')->select(DB::raw('COUNT(id) as last_year_num,
                    SUM(PV) as last_year_PV,
                    SUM(UV) as last_year_UV,
                    SUM(stay_minutes) as last_year_stay_minutes
                    '))->where($where)->first();

                        if($last_year_res->last_year_num ==0){
                            $value->last_year_num = 0;
                            $value->last_year_PV = 0;
                            $value->last_year_UV = 0;
                            $value->last_year_stay_minutes = 0;
                        }else{
                            $value->last_year_num = $last_year_res->last_year_num;
                            $value->last_year_PV = $last_year_res->last_year_PV;
                            $value->last_year_UV = $last_year_res->last_year_UV;
                            $value->last_year_stay_minutes = $last_year_res->last_year_stay_minutes;
                        }

                    }
                }

                break;

            case 3:
                $where = [
                    ['created_at','>=', Carbon::today()->subDays(7)],
                    ['created_at','<=', Carbon::today()],
                ];
                if(ORGCODE)
                    $where[] = ['org_code','=',ORGCODE];
                $select = " 
                    count(id) as total_number,
                    
                    SUM(PV) as total_pv,
                    
                    SUM(UV) as total_uv,
                    
                    SUM(stay_minutes) as total_minutes,
                    
                    bounce_rate,
                    
                    date_format(created_at,'%Y-%m-%d') as date
                    
                  ";

                $res = DB::table('activity')->select(DB::raw($select))->where($where)->groupBy(DB::raw("date_format(created_at,'%Y-%m-%d')"))->orderBy('created_at','asc')->get();
                if($res->count())
                {
                    foreach ($res as $key =>&$value)
                    {
                        $last_year_start_date = Carbon::parse($value->date)->subYear(1);
                        $last_year_end_date = Carbon::parse($value->date)->subYear(1)->subDays(-1);

                        $where = [
                            ['created_at','>=',$last_year_start_date],
                            ['created_at','<=',$last_year_end_date],
                        ];
                        if(ORGCODE)
                            $where[] = ['org_code','=',ORGCODE];
                        $last_year_res = DB::table('activity')->select(DB::raw('COUNT(id) as last_year_num,
                    SUM(PV) as last_year_PV,
                    SUM(UV) as last_year_UV,
                    SUM(stay_minutes) as last_year_stay_minutes
                    '))->where($where)->first();

                        if($last_year_res->last_year_num ==0){
                            $value->last_year_num = 0;
                            $value->last_year_PV = 0;
                            $value->last_year_UV = 0;
                            $value->last_year_stay_minutes = 0;
                        }else{
                            $value->last_year_num = $last_year_res->last_year_num;
                            $value->last_year_PV = $last_year_res->last_year_PV;
                            $value->last_year_UV = $last_year_res->last_year_UV;
                            $value->last_year_stay_minutes = $last_year_res->last_year_stay_minutes;
                        }

                    }
                }
                break;

            case 4:

                $where = [
                    ['created_at','>=', Carbon::today()->subDays(30)],
                    ['created_at','<=', Carbon::today()],
                ];
                if(ORGCODE)
                    $where[] = ['org_code','=',ORGCODE];
                $select = " 
                    count(id) as total_number,

                    SUM(PV) as total_pv,
                    
                    SUM(UV) as total_uv,
                    
                    SUM(stay_minutes) as total_minutes,
                    
                    bounce_rate,
                    
                    CONCAT ( 
                              date_format(created_at,'%Y-%m-'),
                              if(
                                   day(created_at)  div 3*3 <10 ,
                                   if(
                                       day(created_at)  div 3 *3  = 0 , 
                                       '01',
                                        CONCAT('0', day(created_at)  div 3 *3) 
                                     ),
                                     day(created_at)
                               )
                                    
                           )  as date
                    
                  ";

                $res = DB::table('activity')->select(DB::raw($select))->where($where)->groupBy(DB::raw("day(created_at)  div 3"))->orderBy('created_at','asc')->get();
                if($res->count())
                {
                    foreach ($res as $key =>&$value)
                    {
                        $last_year_start_date = Carbon::parse($value->date)->subYear(1);
                        $last_year_end_date = Carbon::parse($value->date)->subYear(1)->subDays(-3);

                        $where = [
                            ['created_at','>=',$last_year_start_date],
                            ['created_at','<=',$last_year_end_date],
                        ];
                        if(ORGCODE)
                            $where[] = ['org_code','=',ORGCODE];
                        $last_year_res = DB::table('activity')->select(DB::raw('COUNT(id) as last_year_num,
                    SUM(PV) as last_year_PV,
                    SUM(UV) as last_year_UV,
                    SUM(stay_minutes) as last_year_stay_minutes
                    '))->where($where)->first();

                        if($last_year_res->last_year_num ==0){
                            $value->last_year_num = 0;
                            $value->last_year_PV = 0;
                            $value->last_year_UV = 0;
                            $value->last_year_stay_minutes = 0;
                        }else{
                            $value->last_year_num = $last_year_res->last_year_num;
                            $value->last_year_PV = $last_year_res->last_year_PV;
                            $value->last_year_UV = $last_year_res->last_year_UV;
                            $value->last_year_stay_minutes = $last_year_res->last_year_stay_minutes;
                        }

                    }
                }
                break;

            case 5:

                $where = [
                    ['created_at','>=', $startDate],
                    ['created_at','<=', $endDate],
                ];
                if(ORGCODE)
                    $where[] = ['org_code','=',ORGCODE];
                $select = " 
                    count(id) as total_number,

                    SUM(PV) as total_pv,
                    
                    SUM(UV) as total_uv,
                    
                    SUM(stay_minutes) as total_minutes,
                    
                    bounce_rate,
                    
                    month(created_at)  as month,
                    
                    CONCAT(date_format(created_at,'%Y-%m-'), '01') as created_at

                  ";

                $res = DB::table('activity')->select(DB::raw($select))->where($where)->groupBy(DB::raw("month(created_at)"))->get();

                if($res->count())
                {
                    foreach ($res as $key =>&$value)
                    {
                        $last_year_start_date = Carbon::parse($value->created_at)->subYear(1);
                        $last_year_end_date = Carbon::parse($value->created_at)->subYear(1)->subDays(30);

                        $where = [
                            ['created_at','>=',$last_year_start_date],
                            ['created_at','<=',$last_year_end_date],
                        ];
                        if(ORGCODE)
                            $where[] = ['org_code','=',ORGCODE];
                        $last_year_res = DB::table('activity')->select(DB::raw('COUNT(id) as last_year_num,
                    SUM(PV) as last_year_PV,
                    SUM(UV) as last_year_UV,
                    SUM(stay_minutes) as last_year_stay_minutes
                    '))->where($where)->first();

                        if($last_year_res->last_year_num ==0){
                            $value->last_year_num = 0;
                            $value->last_year_PV = 0;
                            $value->last_year_UV = 0;
                            $value->last_year_stay_minutes = 0;
                        }else{
                            $value->last_year_num = $last_year_res->last_year_num;
                            $value->last_year_PV = $last_year_res->last_year_PV;
                            $value->last_year_UV = $last_year_res->last_year_UV;
                            $value->last_year_stay_minutes = $last_year_res->last_year_stay_minutes;
                        }

                    }
                }
                break;
        }

        return response()->json(['code'=>200,'data'=>$res]);
    }


}
