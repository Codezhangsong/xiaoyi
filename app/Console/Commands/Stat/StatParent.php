<?php

namespace App\Console\Commands\Stat;

use App\Model\Logs\TaskLog;
use App\Model\Parents;
use App\Model\StatParents;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatParent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:parent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计家长数据定时任务';

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
        $last_week_date = Carbon::now()->subDays(7)->format('Y-m-d');
        try{
            $res = StatParents::where('stat_date',$today)->where('org_code',null)->count();

            if($res){
                throw new \Exception('今日数据已经存在');
            }else{
                //家长总数
                $parents = DB::table('parents')->count();

                $sql  = 'select COUNT(id) as num , city from ty_parents group by city order by num desc limit 1';
                $res = DB::select($sql);
                $city = $res[0]->city;

                //近七天累计家长数
                $weekly_increase = Parents::where([
                    ['reg_date','>=',$last_week_date],
                    ['reg_date','<=',$today],
                ])->count();

                //男女比例
                $sql = 'select count(id) as num ,gender from ty_parents group by gender';
                $gender_result = DB::select($sql);
                foreach ($gender_result as $key =>$value)
                {
                    $value->gender==1?$boy = $value->num:$girl = $value->num;
                }
                $boy_proportion = $parents==0?0:round( $boy/$parents, 2);;
                //年龄最高分布
                $sql = 'select age_temp,count(*) as total from (select age,
                        case
                        when age <31 then \'30-\'
                        when age between 31 and 35 then \'31-35岁\'
                        when age between 36 and 40 then \'36-40岁\'
                        when age between 41 and 45 then \'41-45岁\'
                        when age between 46 and 50 then \'46-50岁\'
                        when age >51 then \'50+\'
                        end as age_temp
                        from ty_parents
                        )t_user  group by age_temp order by total desc limit 1
                        ';
                $age_result = DB::select($sql);
                $age_distinction = $age_result[0]->age_temp;

                $arr = [
                    'total_parents'=>$parents,
                    'last_week_increased_parents'=>$weekly_increase,
                    'sex_proportion'=>$boy_proportion,
                    'age_distribution'=>$age_distinction,
                    'location_distribution'=>$city,
                    'stat_date'=>Carbon::now()->format('Y-m-d'),
                    'updated_at'=>Carbon::now(),
                    'created_at'=>Carbon::now(),
                    'org_code'=>null
                ];

            }

            $id = StatParents::insertGetId($arr);
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
        }
    }
}
