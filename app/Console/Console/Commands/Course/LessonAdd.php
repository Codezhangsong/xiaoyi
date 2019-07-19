<?php

namespace App\Console\Commands\Course;

use App\Model\Course;
use App\Model\Logs\TaskLog;
use App\Services\Utils\Helps;
use App\Services\Utils\Http;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LessonAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lesson:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '增加课节';

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
            $parameter = [];
            $res = Course::where([
                ['status','=',2],
                ['teacher_id','!=', null],
            ])->get();
            $url ='http://08ff2ch7721d.ct-edu.com.cn/thirdparty/liveCourseMaintenance/addLesson';
            if(!$res->count())
                throw new \Exception('暂无可执行的课程');
            foreach ($res as $key =>$value)
            {
                $lesson_objs = DB::table('course_detail')->where('course_id',$value->id)->get();

                foreach ($lesson_objs as $k =>$v)
                {
                    $today = Carbon::now()->format('YmdHis');
                    $start_time = date('YmdHis',strtotime($v->lesson_date.' '.$v->start_time));
                    $end_time = date('YmdHis',strtotime($v->lesson_date.' '.$v->end_time));
                    $lesson_hour = floor((strtotime($end_time)-strtotime($start_time))%86400/3600);
                    $parameter = [
                        'functionCode' => 'addLesson',
                        'teacherId' => $value->teacher_id,
                        'classId' => $value->class_id,
                        'liveContent'=>$v->lesson_name,
                        'code' => $v->code,
                        'liveStartDate' => $start_time,
                        'liveEndDate' => $end_time,
                        'lessonHour'=>$lesson_hour,
                        'liveManNumber'=>15,
                        'platform'=>'liveCourseConnect',
                        'timestamp'=> $today,
                    ];
                    $key = Helps::createSignature($parameter);
                    $arr = [
                        'functionCode' => 'addLesson',
                        'teacherId' => $value->teacher_id,
                        'classId' => $value->class_id,
                        'liveContent'=>urlencode($v->lesson_name),
                        'code' => $v->code,
                        'liveStartDate' => $start_time,
                        'liveEndDate' => $end_time,
                        'lessonHour'=>$lesson_hour,
                        'liveManNumber'=>15,
                        'platform'=>'liveCourseConnect',
                        'timestamp'=> $today,
                        'key'=>$key
                    ];

                    $res = Http::post($url,$arr);
                    if(!$res['code'] != 200)
                        throw new \Exception($res['msg']);
                    if(!isset($res['data']['returnData']['returnCode']) || $res['data']['returnData']['returnCode']!=1)
                        throw new \Exception($res['data']['returnData']['returnMessage']);

                    $lessonId = $res['data']['returnData']['lessonId'];

                    $res = DB::table('course_detail')->where('id',$v->id)->update([
                        'lesson_id'=>$lessonId,
                    ]);

                    if(!$res)
                        throw new \Exception('lesson_id更新失败, id: '.$v->id);
                }
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
            return false;
        }

        TaskLog::insert([
            'task_name'=>$this->signature,
            'result'=>'success',
            'error'=>'',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
    }
}
