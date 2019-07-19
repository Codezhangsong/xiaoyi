<?php

namespace App\Console\Commands\Course;

use App\Model\Course;
use App\Model\Logs\TaskLog;
use App\Services\Utils\Helps;
use App\Services\Utils\Http;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class CourseAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '新增课程';

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
            $res = Course::where([['status','=',2], ['teacher_id','!=', null] ,['class_id','=', null]])->get();
            $url = 'http://08ff2ch7721d.ct-edu.com.cn/thirdparty/liveCourseMaintenance/addLiveClass';
            $date_time = Carbon::now()->format('YmdHis');
            $parameter = [];
            if(!$res->count())
                throw new \Exception('暂无可执行的课程');
            foreach ($res as $key =>$value)
            {
                $parameter = [
                    'functionCode' => 'addLiveCourse',
                    'name' => $value->course_name,
                    'code' => $value->code,
                    'coverImgLink' => $value->cover_img,
                    'enrollStartDate' => date('YmdHis',strtotime($value->start_date)),
                    'enrollEndDate' => date('YmdHis',strtotime($value->end_date)),
                    'classHour'=>  (float)$value->class_hour,
                    'maxNum'=>100,
                    'classType'=> '素质教育',
                    'primeCost'=>(float)$value->price,
                    'cost'=>(float)$value->price,
                    'siteName'=>'添翼申学',
                    'platform'=>'liveCourseConnect' ,
                    'timestamp'=>$date_time,
                ];

                $key = Helps::createSignature($parameter);
                $arr = [
                    'functionCode' => 'addLiveCourse',
                    'name' => urlencode($value->course_name),
                    'code' => $value->code,
                    'coverImgLink' => $value->cover_img, //封面图片
                    'enrollStartDate' => date('YmdHis',strtotime($value->start_date)),
                    'enrollEndDate' => date('YmdHis',strtotime($value->end_date)),
                    'classHour'=>  (float)$value->class_hour,
                    'classType'=>  $value->class_type==null?urlencode('素质教育'):urlencode($value->class_type),
                    'primeCost'=> (float)$value->price,
                    'cost'=>(float)$value->price,
                    'courseIntroduceImg'=> $value->course_introduce_img, //富文本  课程主图
                    'courseInformation'=>  $value->course_content ,//富文本 课程信息
                    'courseTeachersHighlight'=> $value->teacher_info,//富文本 师资介绍
                    'courseHighlight'=> $value->course_feature,//富文本 课程特点
                    'courseLearningContent'=>$value->course_content,//富文本 课程内容
                    'courseObservationStyle'=> '直播',//富文本 观看方式
                    'courseConsultant'=>   $value->course_consultant==null?'暂无':$value->course_consultant,//富文本 温馨提示
                    'courseWarmPrompt'=>  $value->course_prompt==null?'暂无':$value->course_prompt,//富文本 温馨提示
                    'siteName'=>urlencode('添翼申学'),
                    'platform'=>'liveCourseConnect' ,
                    'timestamp'=>$date_time,
                    'maxNum'=>100,
                    'key'=>$key
                ];

                $res = Http::post($url,$arr);
                if($res['code'] != 200)
                    throw new \Exception($res['msg']);
                if(!isset($res['data']['returnData']['classId']))
                    throw new \Exception($res['data']['returnMessage']);

                $classId = $res['data']['returnData']['classId'];

                $res = Course::where('id',$value->id)->update([
                    'class_id'=>$classId,
                    'updated_at'=>Carbon::now()
                ]);

                if(!$res)
                    throw new \Exception('class_id更新失败, courseId: '.$value->id);
            }
        }catch (\Exception $e){
            TaskLog::insert([
                'task_name'=>$this->signature,
                'result'=>'fail',
                'error'=>$e->getMessage(),
                'args'=>json_encode($arr),
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            return false;
        }

        TaskLog::insert([
            'task_name'=>$this->signature,
            'result'=>'success',
            'error'=>'',
            'args'=>json_encode($arr),
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        return true;
    }
}
