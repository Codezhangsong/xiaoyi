<?php

namespace App\Console\Commands\Stat;

use App\Model\Logs\TaskLog;
use App\Model\StatStudents;
use App\Model\Students;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:student';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '学生统计脚本';

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

        $today = Carbon::today();
        $last_week_date = $today->subDays(7)->format('Y-m-d');

        try{
            if(StatStudents::where('stat_date',$today)->where('org_code',null)->count())
                throw new \Exception('今日数据已存在');
            //家长总数
            $students = DB::table('students')->count();
            $sql  = 'select COUNT(id) as num , region from ty_students group by region order by num desc limit 1';
            $res = DB::select($sql);
            if(empty($res))
            {
                $region = '暂无';
            }else{
                $region = $res[0]->region;
            }
            //近七天累计家长数
            $weekly_increase = Students::where([
                ['reg_date','>=',$last_week_date],
                ['reg_date','<=',$today],
            ])->count();

            //男女比例
            $sql = 'select count(id) as num ,gender from ty_students group by gender';
            $gender_result = DB::select($sql);
            foreach ($gender_result as $key =>$value)
            {
                $value->gender==1?$boy = $value->num:$girl = $value->num;
            }
            $boy_proportion = $students==0?0:round( $boy/$students, 2);;
            //年龄最高分布
            $sql = 'select age_temp,count(*) as total from (select age,
                    case
                    when age <7 then \'7-\'
                    when age between 7 and 10 then \'7-10岁\'
                    when age between 11 and 13 then \'11-13岁\'
                    when age between 12 and 15 then \'12-15岁\'
                    when age between 16 and 18 then \'16-18岁\'
                    when age >18 then \'18+\'
                    end as age_temp
                    from ty_students
                    )t_user  group by age_temp order by total desc limit 1
                    ';
            $age_result = DB::select($sql);
            $age_distinction = $age_result[0]->age_temp;

            $arr = [
                'total_students'=>$students,
                'last_week_increased_students'=>$weekly_increase,
                'sex_proportion'=>$boy_proportion,
                'age_distribution'=>$age_distinction,
                'location_distribution'=>$region,
                'stat_date'=>$today,
                'updated_at'=>Carbon::now(),
                'created_at'=>Carbon::now(),
                'org_code'=>null,
            ];

            $id = StatStudents::insertGetId($arr);
            if(!$id)
                throw new \Exception('定时任务插入失败');
            TaskLog::insert([
                'task_name'=>$this->signature,
                'result'=>$id,
                'error'=>'',
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
            echo $e->getMessage();
        }
    }


}
