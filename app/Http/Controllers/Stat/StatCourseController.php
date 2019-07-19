<?php

namespace App\Http\Controllers\Stat;

use App\Model\StatCourse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StatCourseController extends Controller
{
    public function index()
    {
        try{
            $on_year_last_week_compare = 0;
            $on_year_month_compare = 0;
            $on_year_avg_w_compare = 0;
            $l_w_increased_course_compare = 0;
            $l_m_increased_course_compare = 0;
            $l_m_avg_audit_course_compare = 0;

            $date = Carbon::today();
            $last_year = Carbon::now()->subDays(365)->format('Y-m-d');
            $where[] = ['stat_date','=',$date];

            $where[] = ['org_code','=',ORGCODE];

            $today_data = StatCourse::where($where)->first(); //今天的统计数据
            if(!$today_data)
                throw new \Exception('暂无数据');
            $on_year_data = StatCourse::where('stat_date', $last_year)->first(); //去年同期数据

            if($on_year_data){
                if($on_year_data->last_week_course)
                    $on_year_last_week_compare = round(($today_data->last_week_course - $on_year_data->last_week_course)*100/$on_year_data->last_week_course,2);

                if($on_year_data->last_week_course)
                    $on_year_month_compare = round(($today_data->last_mon_course - $on_year_data->last_mon_course)*100/$on_year_data->last_mon_course,2);

                if($on_year_data->l_w_avg_increased_course)
                    $on_year_avg_w_compare = round(($today_data->l_w_avg_increased_course - $on_year_data->l_w_avg_increased_course)*100/$on_year_data->l_w_avg_increased_course,2);

                if($on_year_data->l_w_increased_course)
                    $l_w_increased_course_compare = round(($today_data->l_w_increased_course - $on_year_data->l_w_increased_course)*100/$on_year_data->l_w_increased_course,2);

                if($on_year_data->l_m_increased_course)
                    $l_m_increased_course_compare = round(($today_data->l_m_increased_course - $on_year_data->l_m_increased_course)*100/$on_year_data->l_m_increased_course,2);

                if($on_year_data->l_m_avg_audit_course)
                    $l_m_avg_audit_course_compare = round(($today_data->l_m_avg_audit_course - $on_year_data->l_m_avg_audit_course)*100/$on_year_data->l_m_avg_audit_course,2);
            }

            $arr = [
                'total_course'=>$today_data->total_course,

                'last_week_course'=>$today_data->last_week_course,//近7日总课程数
                'on_year_last_week_compare'=>$on_year_last_week_compare,//近7日总课程数--同比

                'last_mon_course'=>$today_data->last_mon_course,////近30日总课程数
                'on_year_last_month_compare'=>$on_year_month_compare, //近30日总课程数同比

                'l_w_avg_increased_course'=>$today_data->l_w_avg_increased_course,//近7日平均新增课程数
                'l_w_avg_increased_course_compare'=>$on_year_avg_w_compare,//近7日平均新增课程数--同比

                'l_w_increased_course'=>$today_data->l_w_increased_course,
                'l_w_increased_course_compare'=>$l_w_increased_course_compare,//近7日新增课程数--同比

                'l_m_increased_course'=>$today_data->l_m_increased_course,
                'l_m_increased_course_compare'=>$l_m_increased_course_compare, //近30日新增课程数--同比

                'l_m_avg_audit_course'=>$today_data->l_m_avg_audit_course, //近30日平均审核课程数量
                'l_m_avg_audit_course_compare'=>$l_m_avg_audit_course_compare, //近30日平均审核课程数--同比

            ];
        }catch (\Exception $e){
            return response()->json(['code'=>500,'err'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>$arr]);

    }

    public function increaseTrend()
    {
        $startDate = Carbon::today()->subMonths(6);
        $endDate = Carbon::today();
        try{
            $where = [
                ['created_at','>=', $startDate],
                ['created_at','<=', $endDate],
            ];
            if(ORGCODE)
                $where[] = ['org_code','=',ORGCODE];
            $select = " count(id) as num,

                            CONCAT(month(created_at),'月')  as month,
                    
                            CONCAT(date_format(created_at,'%Y-%m-'), '01') as created_at";

            $res = DB::table('course')->select(DB::raw($select))->where($where)->groupBy(DB::raw("month(created_at)"))->get();

            if(!$res->count())
                throw new \Exception('暂无数据');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>$res]);
    }

    public function stat()
    {
        $today = Carbon::now()->format('Y-m-d');
        $last_week_date = Carbon::now()->subDays(7)->format('Y-m-d');
        $last_month_date = Carbon::now()->subDays(30)->format('Y-m-d');

        $total_course = DB::table('course')->count();

        //今天新增课程数
        $today_increase_course = DB::table('course')->where([
            ['reg_date','=',$today],
        ])->count();
        //近七天的新增课程数
        $last_week_increase_course = DB::table('course')->select()->where([
            ['reg_date','<=',$today],
            ['reg_date','>=',$last_week_date],
        ])->count();
        $avg_last_week = round($last_week_increase_course / 7,0);

        //近30天的新增课程数
        $last_month_increase_course = DB::table('course')->select()->where([
            ['reg_date','<=',$today],
            ['reg_date','>=',$last_month_date],
        ])->count();
        $avg_last_month = round($last_month_increase_course / 30,0);

         //近七天的课程数
        $last_week_course = DB::table('course')->where([
            ['start_date','<=',$today],
            ['end_date','>=',$last_week_date],
        ])->count();

        //近三十天的课程数
        $last_month_course = DB::table('course')->where([
            ['start_date','<=',$today],
            ['end_date','>=',$last_month_date],
        ])->count();

        $arr = [
            'total_course'=>$total_course,
            'today_increase_course'=>$today_increase_course,
            'last_week_course'=>$last_week_course,
            'last_mon_course'=>$last_month_course,
            'l_w_avg_increased_course'=>$avg_last_week,
            'l_w_increased_course'=>$last_week_increase_course,
            'l_m_increased_course'=>$last_month_increase_course,
            'l_m_avg_audit_course'=>$avg_last_month,
            'stat_date'=>$today,
            'updated_at'=>Carbon::now(),
            'created_at'=>Carbon::now(),
        ];

        $res = StatCourse::insert($arr);
    }

    public function trend(Request $request)
    {
        $date_type = $request->input('dateType');
        $type = $request->input('type');
        $startDate = $request->input('startDate',Carbon::today()->subMonths(7));
        $endDate = $request->input('endDate',Carbon::today());
        try{
            switch ($type)
            {
                case 1:
                    switch ($date_type)
                    {
                        case 1:
                            $where = [
                                ['created_at','>=', Carbon::today()],
                                ['created_at','<=', Carbon::tomorrow()],
                                ['status','=',4],
                            ];
                            if(ORGCODE)
                                $where[] = ['org_code','=',ORGCODE];
                            $select = " count(id) as total_number,
                            CONCAT ( 
                              date_format(created_at,'%Y-%m-%d '),
                              if(hour(created_at) div 3 * 3 <10,  CONCAT('0',hour(created_at) div 3 * 3)     ,  hour(created_at) div 3 * 3),
                              ':00:00'
                              
                            )  as created_at ";

                            $res = DB::table('course')->select(DB::raw($select))->where($where)->groupBy(DB::raw("hour(created_at)  div 3"))->get();

                            if($res->count())
                            {
                                foreach ($res as $key =>&$value)
                                {
                                    $last_year_start_date = Carbon::parse($value->created_at)->subYear(1);
                                    $last_year_end_date = Carbon::parse($value->created_at)->subYear(1)->subHours(-3);

                                    $where = [
                                        ['created_at','>=',$last_year_start_date],
                                        ['created_at','<=',$last_year_end_date],
                                        ['status','=',4],
                                    ];
                                    if(ORGCODE)
                                        $where[] = ['org_code','=',ORGCODE];
                                    $last_year_res = DB::table('course')->select(DB::raw('COUNT(id) as last_year_num
                    '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;

                                }
                            }
                            break;

                        case 2:
                            $where = [
                                ['created_at','>=', Carbon::yesterday()],
                                ['created_at','<=', Carbon::today()],
                                ['status','=',4],
                            ];
                            if(ORGCODE)
                                $where[] = ['org_code','=',ORGCODE];
                            $select = " count(id) as total_number,
                            CONCAT ( 
                              date_format(created_at,'%Y-%m-%d '),
                              if(hour(created_at) div 3 * 3 <10,  CONCAT('0',hour(created_at) div 3 * 3)     ,  hour(created_at) div 3 * 3),
                              ':00:00'
                              
                            )  as created_at ";

                            $res = DB::table('course')->select(DB::raw($select))->where($where)->groupBy(DB::raw("hour(created_at)  div 3"))->get();

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
                                    $last_year_res = DB::table('course')->select(DB::raw('COUNT(id) as last_year_num
                    '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;

                                }
                            }

                            break;

                        case 3:
                            $where = [
                                ['created_at','>=', Carbon::today()->subDays(7)],
                                ['created_at','<=', Carbon::today()],
                                ['status','=',4],
                            ];
                            if(ORGCODE)
                                $where[] = ['org_code','=',ORGCODE];
                            $select = " 
                            count(id) as total_number,
                            
                            date_format(created_at,'%Y-%m-%d') as date
                            
                          ";

                            $res = DB::table('course')->select(DB::raw($select))->where($where)->groupBy(DB::raw("date_format(created_at,'%Y-%m-%d')"))->get();
                            if($res->count())
                            {
                                foreach ($res as $key =>&$value)
                                {
                                    $last_year_start_date = Carbon::parse($value->date)->subYear(1);
                                    $last_year_end_date = Carbon::parse($value->date)->subYear(1)->subDay(-1);

                                    $where = [
                                        ['created_at','>=',$last_year_start_date],
                                        ['created_at','<=',$last_year_end_date],
                                    ];
                                    if(ORGCODE)
                                        $where[] = ['org_code','=',ORGCODE];
                                    $last_year_res = DB::table('course')->select(DB::raw('COUNT(id) as last_year_num
                    '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;
                                }
                            }
                            break;

                        case 4:

                            $where = [
                                ['created_at','>=', Carbon::today()->subDays(30)],
                                ['created_at','<=', Carbon::today()],
                                ['status','=',4],
                            ];
                            if(ORGCODE)
                                $where[] = ['org_code','=',ORGCODE];
                            $select = " 
                            count(id) as total_number,
                            
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
                                            
                                   )  as date";

                            $res = DB::table('course')->select(DB::raw($select))->where($where)->groupBy(DB::raw("day(created_at)  div 3"))->orderBy('date')->get();

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
                                    $last_year_res = DB::table('course')->select(DB::raw('COUNT(id) as last_year_num
                    '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;
                                }
                            }
                            break;

                        case 5:
                            $where = [
                                ['created_at','>=', $startDate],
                                ['created_at','<=', $endDate],
                                ['status','=',4],
                            ];
                            if(ORGCODE)
                                $where[] = ['org_code','=',ORGCODE];
                            $select = " count(id) as total_number,
                            month(created_at)  as month,
                            CONCAT(date_format(created_at,'%Y-%m-'), '01') as created_at";

                            $res = DB::table('course')->select(DB::raw($select))->where($where)->groupBy(DB::raw("month(created_at)"))->get();
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
                                    $last_year_res = DB::table('course')->select(DB::raw('COUNT(id) as last_year_num
                    '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;
                                }
                            }
                            break;
                    }
                    break;
                case 2:
                    switch ($date_type)
                    {
                        case 1:
                            $where = [
                                ['created_at','>=', Carbon::today()],
                                ['created_at','<=', Carbon::tomorrow()],
                                ['status','=',1],
                            ];
                            if(ORGCODE)
                                $where[] = ['org_code','=',ORGCODE];
                            $select = " count(id) as total_number,
                            CONCAT ( 
                              date_format(created_at,'%Y-%m-%d '),
                              if(hour(created_at) div 3 * 3 <10,  CONCAT('0',hour(created_at) div 3 * 3)     ,  hour(created_at) div 3 * 3),
                              ':00:00'
                              
                            )  as created_at ";

                            $res = DB::table('course')->select(DB::raw($select))->where($where)->groupBy(DB::raw("hour(created_at)  div 3"))->get();

                            if($res->count())
                            {
                                foreach ($res as $key =>&$value)
                                {
                                    $last_year_start_date = Carbon::parse($value->created_at)->subYear(1);
                                    $last_year_end_date = Carbon::parse($value->created_at)->subYear(1)->subHours(-3);

                                    $where = [
                                        ['created_at','>=',$last_year_start_date],
                                        ['created_at','<=',$last_year_end_date],
                                        ['status','=',1],
                                    ];
                                    if(ORGCODE)
                                        $where[] = ['org_code','=',ORGCODE];
                                    $last_year_res = DB::table('course')->select(DB::raw('COUNT(id) as last_year_num
                    '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;

                                }
                            }
                            break;

                        case 2:
                            $where = [
                                ['created_at','>=', Carbon::yesterday()],
                                ['created_at','<=', Carbon::today()],
                                ['status','=',1],
                            ];
                            if(ORGCODE)
                                $where[] = ['org_code','=',ORGCODE];
                            $select = " count(id) as total_number,
                            CONCAT ( 
                              date_format(created_at,'%Y-%m-%d '),
                              if(hour(created_at) div 3 * 3 <10,  CONCAT('0',hour(created_at) div 3 * 3)     ,  hour(created_at) div 3 * 3),
                              ':00:00'
                              
                            )  as created_at ";

                            $res = DB::table('course')->select(DB::raw($select))->where($where)->groupBy(DB::raw("hour(created_at)  div 3"))->get();

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
                                    $last_year_res = DB::table('course')->select(DB::raw('COUNT(id) as last_year_num
                    '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;

                                }
                            }

                            break;

                        case 3:
                            $where = [
                                ['created_at','>=', Carbon::today()->subDays(7)],
                                ['created_at','<=', Carbon::today()],
                                ['status','=',1],
                            ];
                            if(ORGCODE)
                                $where[] = ['org_code','=',ORGCODE];
                            $select = " 
                            count(id) as total_number,
                            
                            date_format(created_at,'%Y-%m-%d') as date
                            
                          ";

                            $res = DB::table('course')->select(DB::raw($select))->where($where)->groupBy(DB::raw("date_format(created_at,'%Y-%m-%d')"))->get();
                            if($res->count())
                            {
                                foreach ($res as $key =>&$value)
                                {
                                    $last_year_start_date = Carbon::parse($value->date)->subYear(1);
                                    $last_year_end_date = Carbon::parse($value->date)->subYear(1)->subDay(-1);

                                    $where = [
                                        ['created_at','>=',$last_year_start_date],
                                        ['created_at','<=',$last_year_end_date],
                                    ];
                                    if(ORGCODE)
                                        $where[] = ['org_code','=',ORGCODE];
                                    $last_year_res = DB::table('course')->select(DB::raw('COUNT(id) as last_year_num
                    '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;
                                }
                            }
                            break;

                        case 4:

                            $where = [
                                ['created_at','>=', Carbon::today()->subDays(30)],
                                ['created_at','<=', Carbon::today()],
                                ['status','=',1],
                            ];
                            if(ORGCODE)
                                $where[] = ['org_code','=',ORGCODE];
                            $select = " 
                            count(id) as total_number,
                            
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
                                            
                                   )  as date";

                            $res = DB::table('course')->select(DB::raw($select))->where($where)->groupBy(DB::raw("day(created_at)  div 3"))->orderBy('date')->get();

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
                                    $last_year_res = DB::table('course')->select(DB::raw('COUNT(id) as last_year_num
                    '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;
                                }
                            }
                            break;

                        case 5:
                            $where = [
                                ['created_at','>=', $startDate],
                                ['created_at','<=', $endDate],
                                ['status','=',1],
                            ];
                            if(ORGCODE)
                                $where[] = ['org_code','=',ORGCODE];
                            $select = " count(id) as total_number,

                            CONCAT(month(created_at),'月')  as month,
                    
                            CONCAT(date_format(created_at,'%Y-%m-'), '01') as created_at";

                            $res = DB::table('course')->select(DB::raw($select))->where($where)->groupBy(DB::raw("month(created_at)"))->get();

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
                                    $last_year_res = DB::table('course')->select(DB::raw('COUNT(id) as last_year_num
                    '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;
                                }
                            }
                            break;
                    }

                    break;

            }

            if(empty($res))
                throw new \Exception('暂无数据');

        }catch (\Exception $e){
            return response()->json(['code'=>500,'err'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>$res]);

    }

}
