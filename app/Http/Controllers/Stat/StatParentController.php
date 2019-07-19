<?php

namespace App\Http\Controllers\Stat;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class StatParentController extends Controller
{
    //数据统计
    public function stat()
    {
        try{
            $where[] = ['org_code','=',ORGCODE];
            $where[] = ['stat_date','=',Carbon::today()];
            $res = DB::table('stat_parents')->where($where)->orderBy('created_at','desc')->first();
            if(!$res)
                throw new \Exception('暂无数据');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }

        return ['code'=>200,'data'=>$res];
    }


    //新增家长整体趋势
    public function increaseTrend(Request $request)
    {
        $date_type = $request->input('dateType',4);
        $type = $request->input('type');
        $startDate = $request->input('startDate',Carbon::now()->subMonth(7));
        $endDate = $request->input('endDate',Carbon::now());
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
                            ];
                            if(ORGCODE)
                                $where[] = ['org_code','=',ORGCODE];
                            $select = " 
                        count(id) as total_number,
                        
                        
                        CONCAT ( 
                                  date_format(created_at,'%Y-%m-%d '),
                                  if(hour(created_at) div 3 * 3 <10,  CONCAT('0',hour(created_at) div 3 * 3)     ,  hour(created_at) div 3 * 3),
                                  ':00:00'
                                  
                                )  as created_at ";

                            $res = DB::table('parents')->select(DB::raw($select))->where($where)->groupBy(DB::raw("hour(created_at)  div 3"))->get();

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

                                    $last_year_res = DB::table('parents')->select(DB::raw('
                        COUNT(id) as last_year_num
                        '))->where($where)->first();

                                    $value->last_year_num = $last_year_res->last_year_num;

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
                            
                            CONCAT ( 
                                      date_format(created_at,'%Y-%m-%d '),
                                      if(hour(created_at) div 3 * 3 <10,  CONCAT('0',hour(created_at) div 3 * 3)     ,  hour(created_at) div 3 * 3),
                                      ':00:00'
                                      
                                    )  as created_at ";

                            $res = DB::table('parents')->select(DB::raw($select))->where($where)->groupBy(DB::raw("hour(created_at)  div 3"))->get();

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
                                    $last_year_res = DB::table('parents')->select(DB::raw('COUNT(id) as last_year_num
                    
                    '))->where($where)->first();
                                        $value->last_year_num = $last_year_res->last_year_num;


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
                    
                    date_format(created_at,'%Y-%m-%d') as date
                    
                  ";

                            $res = DB::table('parents')->select(DB::raw($select))->where($where)->groupBy(DB::raw("date_format(created_at,'%Y-%m-%d')"))->get();
                            if($res->count())
                            {
                                foreach ($res as $key =>&$value)
                                {
                                    $last_year_start_date = Carbon::parse($value->date)->subYear(1);
                                    $last_year_end_date = Carbon::parse($value->date)->subYear(1)->subHours(-3);

                                    $where = [
                                        ['created_at','>=',$last_year_start_date],
                                        ['created_at','<=',$last_year_end_date],
                                    ];
                                    if(ORGCODE)
                                        $where[] = ['org_code','=',ORGCODE];
                                    $last_year_res = DB::table('parents')->select(DB::raw('COUNT(id) as last_year_num
                    
                    '))->where($where)->first();
                                        $value->last_year_num = $last_year_res->last_year_num;


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

                            $res = DB::table('parents')->select(DB::raw($select))->where($where)->groupBy(DB::raw("day(created_at)  div 3"))->orderBy('date')->get();

                            if($res->count())
                            {
                                foreach ($res as $key =>&$value)
                                {
                                    $last_year_start_date = Carbon::parse($value->date)->subYear(1);
                                    $last_year_end_date = Carbon::parse($value->date)->subYear(1)->subHours(-3);

                                    $where = [
                                        ['created_at','>=',$last_year_start_date],
                                        ['created_at','<=',$last_year_end_date],
                                    ];
                                    if(ORGCODE)
                                        $where[] = ['org_code','=',ORGCODE];
                                    $last_year_res = DB::table('parents')->select(DB::raw('COUNT(id) as last_year_num
                    
                    '))->where($where)->first();
                                        $value->last_year_num = $last_year_res->last_year_num;
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
                    
                    month(created_at)  as month,
                    
                    CONCAT(date_format(created_at,'%Y-%m-'), '01') as created_at

                  ";

                            $res = DB::table('parents')->select(DB::raw($select))->where($where)->groupBy(DB::raw("month(created_at)"))->get();

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
                                    $last_year_res = DB::table('parents')->select(DB::raw('COUNT(id) as last_year_num
                    
                    '))->where($where)->first();
                                        $value->last_year_num = $last_year_res->last_year_num;


                                }
                            }
                            break;
                    }
                    break;
                case 2:
                    switch ($date_type){
                        case 1:

                            $where = [
                                ['created_at','>=', Carbon::today()],
                                ['created_at','<=', Carbon::tomorrow()],
                            ];
                            if(ORGCODE)
                                $where[] = ['org_code','=',ORGCODE];
                            $select = " 
                            SUM(parent_num) as total_number,
                            
                            
                            CONCAT ( 
                                      date_format(created_at,'%Y-%m-%d '),
                                      if(hour(created_at) div 3 * 3 <10,  CONCAT('0',hour(created_at) div 3 * 3)     ,  hour(created_at) div 3 * 3),
                                      ':00:00'
                                      
                                    )  as created_at ";

                            $res = DB::table('activity')->select(DB::raw($select))->where($where)->groupBy(DB::raw("hour(created_at)  div 3"))->get();

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
                                    $last_year_res = DB::table('activity')->select(DB::raw('
                            COUNT(id) as last_year_num
                            '))->where($where)->first();

                                    $value->last_year_num = $last_year_res->last_year_num;

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
                                SUM(parent_num) as total_number,
                                
                                CONCAT ( 
                                          date_format(created_at,'%Y-%m-%d '),
                                          if(hour(created_at) div 3 * 3 <10,  CONCAT('0',hour(created_at) div 3 * 3)     ,  hour(created_at) div 3 * 3),
                                          ':00:00'
                                          
                                        )  as created_at ";

                            $res = DB::table('activity')->select(DB::raw($select))->where($where)->groupBy(DB::raw("hour(created_at)  div 3"))->get();

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
                                    $last_year_res = DB::table('activity')->select(DB::raw('COUNT(id) as last_year_num
                        
                        '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;


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
                        SUM(parent_num) as total_number,
                        
                        date_format(created_at,'%Y-%m-%d') as date
                        
                      ";

                            $res = DB::table('activity')->select(DB::raw($select))->where($where)->groupBy(DB::raw("date_format(created_at,'%Y-%m-%d')"))->get();
                            if($res->count())
                            {
                                foreach ($res as $key =>&$value)
                                {
                                    $last_year_start_date = Carbon::parse($value->date)->subYear(1);
                                    $last_year_end_date = Carbon::parse($value->date)->subYear(1)->subHours(-3);

                                    $where = [
                                        ['created_at','>=',$last_year_start_date],
                                        ['created_at','<=',$last_year_end_date],
                                    ];
                                    if(ORGCODE)
                                        $where[] = ['org_code','=',ORGCODE];
                                    $last_year_res = DB::table('activity')->select(DB::raw('COUNT(id) as last_year_num
                        
                        '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;
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
                        SUM(parent_num) as total_number,
    
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

                            $res = DB::table('activity')->select(DB::raw($select))->where($where)->groupBy(DB::raw("day(created_at)  div 3"))->orderBy('date')->get();

                            if($res->count())
                            {
                                foreach ($res as $key =>&$value)
                                {
                                    $last_year_start_date = Carbon::parse($value->date)->subYear(1);
                                    $last_year_end_date = Carbon::parse($value->date)->subYear(1)->subHours(-3);

                                    $where = [
                                        ['created_at','>=',$last_year_start_date],
                                        ['created_at','<=',$last_year_end_date],
                                    ];
                                    if(ORGCODE)
                                        $where[] = ['org_code','=',ORGCODE];
                                    $last_year_res = DB::table('activity')->select(DB::raw('COUNT(id) as last_year_num
                        
                        '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;
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
                        SUM(parent_num) as total_number,
                        
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
                                    $last_year_res = DB::table('activity')->select(DB::raw('COUNT(id) as last_year_num
                        
                        '))->where($where)->first();
                                    $value->last_year_num = $last_year_res->last_year_num;
                                }
                            }
                            break;
                    }

            }

            if(empty($res))
                throw new \Exception('暂无数据');

        }catch (\Exception $e){
            return response()->json(['code'=>500,'err'=>$e->getMessage()]);
        }

        return ['code'=>200,'data'=>['data'=>$res]];
    }

}
