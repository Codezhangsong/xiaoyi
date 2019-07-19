<?php

namespace App\Console\Commands\Stat;
use App\Model\Logs\TaskLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StatCourse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:course';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '后台课程统计脚本';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
                $today = Carbon::today();
                $where[] = ['stat_date','=',$today];
                $where[] = ['org_code','=',null];
                $res = DB::table('stat_course')->where($where)->count();

                if($res)
                    return false;
                $last_week_date = Carbon::now()->subDays(7)->format('Y-m-d');
                $last_month_date = Carbon::now()->subDays(30)->format('Y-m-d');
                $total_course = DB::table('course')->count();

                //今天新增课程数
                $today_increase_course = DB::table('course')->where([
                    ['reg_date','=',$today],
                ])->count();

                $today_aduit_course = DB::table('course')->where([
                    ['reg_date','=',$today],
                    ['status','=',5],
                ])->count();

                //近七天的新增课程数
                $last_week_increase_course = DB::table('course')->where([
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
                    'today_audit_course'=>$today_aduit_course,
                    'last_week_course'=>$last_week_course,
                    'last_mon_course'=>$last_month_course,
                    'l_w_avg_increased_course'=>$avg_last_week,
                    'l_w_increased_course'=>$last_week_increase_course,
                    'l_m_increased_course'=>$last_month_increase_course,
                    'l_m_avg_audit_course'=>$avg_last_month,
                    'stat_date'=>$today,
                    'updated_at'=>Carbon::now(),
                    'created_at'=>Carbon::now(),
                    'org_code'=>null,
                ];

                $id = \App\Model\StatCourse::insertGetId($arr);
                if(!$id)
                    throw new \Exception('定时任务插入失败');
                TaskLog::insert([
                    'task_name'=>$this->signature,
                    'result'=>$id,
                    'error'=>null,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);

        }catch (\Exception $e){
            Log::info($e->getMessage());
            TaskLog::insert([
                'task_name'=>$this->signature,
                'result'=>'fail',
                'error'=>$e->getMessage(),
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
        }

    }
}
