<?php

namespace App\Console\Commands\Course;

use App\Model\Course;
use App\Model\Logs\TaskLog;
use App\Services\Utils\Helps;
use App\Services\Utils\Http;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TeacherAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teacher:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '添加教师接口';

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
           
            //$courses = Course::where('teacher_id',null)->get();
          //  var_dump($courses);

          $courses = Course::where('id','169')->get();
          var_dump($courses);
        
          $jigou =env('JIGOU');
            $parameter = [];
            if(!$courses->count())
                throw new \Exception('无可添加的教师');

               
            foreach ($courses as $key =>$value)
            {
                $mobile = $value->account;
              
                
               //$mobile = '13122799016';
                $now = date('YmdHis',time());
                $arr = [
                    'functionCode' => 'addTeacher',
                    'userId' => $mobile,
                    'siteName' => $jigou,
                    'orient' => '暂无',
                    'note' => '暂无',
                    'platform'=>'liveCourseConnect',
                    'timestamp'=>$now,
                ];
                $sign = Helps::createSignature($arr);

               $userId = Helps::des_ecb_encrypt($mobile,$key = 'tyxs9sx');
            //   $userId = 'a6c4f2451a964475344ef5f1faebf7ef';
                $parameter = [
                    'functionCode' => 'addTeacher',
                    'userId' => $userId,
                    'siteName' => urlencode($jigou),
                    'orient' => urlencode('暂无'),
                    'note' => urlencode('暂无'),
                    'platform'=>'liveCourseConnect',
                    'timestamp'=>$now,
                    'key'=>$sign
                ];
                $url =env('ZHIBO_URL');
                $url .= 'thirdparty/liveCourseMaintenance/addTeacher';
                $res = Http::post($url,$parameter);

                var_dump($parameter,$res);

               
               
                if($res['code'] != 200)
                    throw new \Exception($res['msg']);
                if(!isset($res['data']['returnData']['teacherId']))
                    throw new \Exception($res['data']['returnMessage']);

                $teacher_id = $res['data']['returnData']['teacherId'];


                $res = Course::where('id',$value->id)->update([
                    'teacher_id'=>$teacher_id,
                    'updated_at'=>Carbon::now()
                ]);
                    
                if(!$res)
                    throw new \Exception('教师id更新失败'.$value->id);

                TaskLog::insert([
                    'task_name'=>$this->signature,
                    'result'=>'success',
                    'error'=>'',
                    'args'=>json_encode($parameter),
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);
            }
        }catch (\Exception $e){
            TaskLog::insert([
                'task_name'=>$this->signature,
                'result'=>'fail',
                'args'=>json_encode($parameter),
                'error'=>$e->getMessage(),
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            return false;
        }

       return true;
    }
}
