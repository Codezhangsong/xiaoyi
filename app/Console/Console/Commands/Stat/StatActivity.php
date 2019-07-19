<?php

namespace App\Console\Commands\Stat;

use App\Model\Activity;
use App\Model\Logs\TaskLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '活动统计脚本';

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
        $today = Carbon::now()->format('Y-m-d');
        $res = DB::table('stat_course')->where('stat_date',$today)->count();
        if($res)
            return false;
        try{
            $total_activity = Activity::count();

            if(!$total_activity)
                throw new \Exception('暂无活动数据');
            $res = DB::table('activity')->select(DB::raw('SUM(PV) as total_PV, SUM(UV) as total_UV,
            SUM(student_num + parent_num) as total_number , SUM(bounce_rate) as total_bounce, SUM(stay_minutes) total_mins'))->first();
            $avg_bounce = $res->total_bounce/$total_activity;

            $arr = [
                'total_PV'=>$res->total_PV,
                'total_UV'=>$res->total_UV,
                'total_number'=>$res->total_number,
                'total_minutes'=>$res->total_mins,
                'avg_bounce'=>$avg_bounce,
                'stat_date'=>$today,
                'updated_at'=>Carbon::now(),
                'created_at'=>Carbon::now(),
            ];

            $id = \App\Model\StatActivity::insertGetId($arr);
            if(!$id)
                throw new \Exception('活动统计定时任务插入失败');
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
