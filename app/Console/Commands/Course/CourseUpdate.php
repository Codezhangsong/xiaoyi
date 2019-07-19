<?php

namespace App\Console\Commands\Course;

use App\Model\Course;
use App\Model\Logs\TaskLog;
use App\Services\Utils\Helps;
use App\Services\Utils\Http;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CourseUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '远程查询课程状态';

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
            $res = Course::where([
                ['status','=', 5],
                ['class_id','!=', null],
            ])->get();

            $url =env('ZHIBO_URL');
            $url .='thirdparty/liveCourseMaintenance/getClassDetail';
            $date_time = Carbon::now()->format('YmdHis');
            $parameter = [];
            if(!$res->count())
                throw new \Exception('暂无可执行的课程');
            foreach ($res as $key =>$value)
            {
                $parameter = [
                    'functionCode' => 'getClassDetail',
                    'classId' => $value->class_id,
                    'platform'=>  'liveCourseConnect' ,
                    'timestamp'=>  $date_time,
                ];
                $key = Helps::createSignature($parameter);
                $arr = [
                    'functionCode' => 'getClassDetail',
                    'classId' =>  $value->class_id,
                    'platform'=>  'liveCourseConnect' ,
                    'timestamp'=>  $date_time,
                    'key'=>$key
                ];
                $res = Http::post($url,$arr);

            
                if($res['code']!=200)
                    throw new \Exception($res['msg']);
                if($res['data']['returnCode'] != 1)
                    throw new \Exception($res['data']['returnMessage']);
                if(!isset($res['data']['returnData']['isSearch']))
                    throw new \Exception('isSearch 字段不存在');
                    if($res['code']['data']['returnData']['isSearch'] == 1)
                        Course::where('id',$value->id)->update(['status'=>4,'updated_at'=>Carbon::now()]);
            }

        }catch (\Exception $e){
            TaskLog::insert([
                'task_name'=>$this->signature,
                'result'=>'fail',
                'error'=>$e->getMessage(),
                'args'=>json_encode($parameter),
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            return ;
        }

        TaskLog::insert([
            'task_name'=>$this->description,
            'result'=>'success',
            'error'=>'',
            'args'=>json_encode($parameter),
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);


    }
}
