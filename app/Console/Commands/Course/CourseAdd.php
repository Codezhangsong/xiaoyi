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
           // $res = Course::where([['status','=',5], ['teacher_id','!=', null] ,['class_id','=', null]])->get();
           
            var_dump(123);
            
     

            $url =env('ZHIBO_URL');
            $jigou=env('JIGOU');
           $res = Course::where([['id','=',169]])->limit(1)->get();
           // var_dump($res);exit;
          //  $url1 = 'http://08ff2ch7721d.ct-edu.com.cn/thirdparty/liveCourseMaintenance/addLiveClass';
            
            $url .='thirdparty/liveCourseMaintenance/addLiveClass';

           
          
           

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
                    'classType'=> $value->class_type,
                    'primeCost'=>(float)$value->price,
                    'cost'=>(float)$value->price,
                    'siteName'=>$jigou,
                    'platform'=>'liveCourseConnect' ,
                    'timestamp'=>$date_time,
                ];

                $key = Helps::createSignature($parameter);

                $courseInformation = "<div id='courseInformation' ng-bind-html='courseDetailModel.courseInformation' ng-if='classPreviewData.class.courseInformation.length > 0' class='ng-binding ng-scope'>
                                        <table class='course-title-table' style='width:100%;border-style: hidden' cellspacing='0' border='0'>
                                        <tbody>
                                        <tr class='firstRow'>
                                        <td style='padding: 0px 0px 10px; word-break: break-all;'>
                                        <p style='margin: 35px 0 10px 18px;height: 32px;width: 8px;background-color: #56b430;float:left;'>
                                        <br>
                                         &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
                                         <p style='font-size: 28px;color: #4c4c4c;margin: 35px 0 10px 15px;height: 32px;line-height:32px;float:left;'>课程信息</p>
                                         </td></tr></tbody></table>
                                         <table class='course-information-table' frame='void' style='font-size: 28px;border-collapse: separate;padding: 0px 18px;border:  none;border-style: hidden;width: 100%;margin-bottom: 0;' cellspacing='0' border='0' align='center'><tbody rules='none'><tr rules='none' class='firstRow'>
                                         <td style='text-align: center;border-style: hidden;background-color: rgb(86, 180, 48);padding: 10px;white-space: nowrap;vertical-align: middle;color: rgb(255, 255, 255);word-break: break-all;' width='1%' valign='top'>难度说明</td>
                                         <td style='border-style: hidden; background: rgb(242, 242, 242); padding: 20px 28px; word-break: break-all;' width='99%' valign='top'>
                                         $value->class_difficult
                                         </tr>
                                         </tbody></table><p style='padding: 0;margin: 0 18px;background-color:rgb(242,242,242);height:5px;'><br></p><table frame='void' style='font-size: 28px;border-collapse: separate;padding: 0 18px;border:  none;border-style: hidden;width: 100%;margin-bottom: 0;' cellspacing='0' border='0' align='center'>
                                         <tbody rules='none'><tr rules='none' class='firstRow'><td style='text-align: center;border-style: hidden;background-color: rgb(86,180,48);padding: 10px;white-space:nowrap;vertical-align: middle;color: #fff;' width='1%' valign='top'>适合学员</td><td style='font-size: 22px;word-break: break-all;border-style: hidden;background: white;padding: .8em .5em;vertical-align: middle;' width='99%' valign='top'>
                                         <ul style='list-style-type: disc;' class='list-ul list-paddingleft-2'>
                                         <li><p style='margin-top: 0px; margin-bottom: 0px; padding: 0px;'>$value->class&nbsp;</p></li>
                                         </ul></td></tr></tbody></table><p style='padding: 0;margin: 0 18px;background-color:rgb(242,242,242);height:5px;'><br></p>
                                         <table frame='void' style='font-size: 28px;border-collapse: separate;padding: 0 18px;border:  none;border-style: hidden;width: 100%;margin-bottom: 0;' cellspacing='0' border='0' align='center'>
                                         <tbody rules='none'><tr rules='none' class='firstRow'>
                                         <td style='text-align: center;border-style: hidden;background-color: rgb(86,180,48);padding: 10px;white-space:nowrap;vertical-align: middle;color: #fff;' width='1%' valign='top'>学习目标</td>
                                         <td style='word-break: break-all;border-style: hidden;background: rgb(242,242,242);padding: .8em .5em;vertical-align: middle;font-size: 22px;' width='99%' valign='top'>
                                         <ul class=' list-paddingleft-2' style='list-style-type: disc;'><li><p><span style='font-size: 22px; background-color: rgb(242, 242, 242);'>$value->course_obj</span></p></li></ul>
                                         </td></tr></tbody></table><p style='padding: 0;margin: 0 18px;background-color:rgb(242,242,242);height:5px;'><br></p>
                                        <p style='padding: 0;margin: 0 18px;background-color:rgb(242,242,242);height:5px;'><br></p>
                                         <table frame='void' style='font-size: 28px;border-collapse: separate;padding: 0 18px;border:  none;border-style: hidden;width: 100%;margin-bottom: 20px;' cellspacing='0' border='0' align='center'><tbody rules='none'>
                                         <tr rules='none' class='firstRow'><td style='text-align: center;border-style: hidden;background-color: rgb(86,180,48);padding: 10px;white-space:nowrap;vertical-align: middle;color: #fff;' width='1%' valign='top'>配套讲义</td>
                                         <td style='word-break: break-all;border-style: hidden;background: rgb(242,242,242);/* padding: .8em .8em; */padding: 20px 28px;vertical-align:  middle;font-size: 22px;' width='99%' valign='top'>$value->textbook</td></tr>
                                         </tbody></table><p><br></p></div>";

                $teacher_high_light = "<div id='courseTeachersHighlight' ng-bind-html='courseDetailModel.courseTeachersHighlight' ng-if='classPreviewData.class.courseTeachersHighlight.length > 0' class='ng-binding ng-scope'>
                                        <table id='teachers-highlight' style='width:100%;border-style: hidden' width='493' cellspacing='0' border='0'><tbody><tr class='firstRow'><td style='padding: 0px; word-break: break-all;' width='235'><p style='margin:0px 0 10px 18px;height: 35px;width: 8px;background-color: #56b430;float:left'><br>
                                         &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p style='font-size: 28px;color: #4c4c4c;margin:0px 0 10px 15px;height: 35px;line-height:35px;float:left;'>师资介绍</p></td></tr></tbody></table><table frame='void' 
                                         style='font-size: 20px;border-collapse: separate;padding: 0 18px;border:  none;border-style: hidden;width: 100%;margin-bottom: 0;' width='493' cellspacing='0' border='0' align='center'>
                                         <tbody rules='none'><tr rules='none' style='background-color: rgb(238,247,234);' class='firstRow'><td style='text-align: center; border-style: hidden; padding: 30px; white-space: nowrap; vertical-align: middle; color: rgb(255, 255, 255); word-break: break-all;' valign='top'>
                                         <p style='background-color:rgb(252,206,47);border-radius: 100px;width:  144px;height: 148px;'><img src='$value->teacher_img' style='color: rgb(255, 255, 255);
                                         font-size: 20px;text-align: center;width: 144px;height: 144px;border-radius: 100px;'>
                                         &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p></td><td style='word-break: break-all;border-style: hidden;padding: 30px 30px 30px 0;' valign='top'><p style='background-color:rgb(86,180,48);width: 170px;height: 42px;font-size:25px;border-radius:30px;color: #fff;text-align:  center;line-height: 42px;'>
                                         $value->teacher_name</p><p style='padding-top: 16px;'><span style='font-size: 20px; background-color: rgb(238, 247, 234);'>".$value->teacher_info."</span></p></td></tr></tbody></table><p><br></p></div>";

                $features = explode('|',$value->course_feature);
                $course_high_light = "<div id='courseHighlight' ng-bind-html='courseDetailModel.courseHighlight' ng-if='classPreviewData.class.courseHighlight.length > 0' class='ng-binding ng-scope'><table style='width:100%;border-style: hidden' width='493' cellspacing='0' border='0'>

                                        <tbody><tr class='firstRow'><td style='padding: 0px; word-break: break-all;' width='112'><p style='margin:0px 0 10px 18px;height: 32px;width: 8px;background-color: #56b430;float:left'><br>
                                         &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p style='font-size: 28px;color: #4c4c4c;margin:0px 0 10px 15px;height: 32px;line-height:32px;float:left;'>课程亮点</p></td></tr></tbody></table>
                                        
                                        
                                         <table frame='void' style='font-size: 20px;border-collapse: separate;padding: 0 18px;border:none;border-style: hidden;width: 100%;margin-bottom: 0;table-layout: fixed;' width='493' cellspacing='0' border='0' align='center'><tbody rules='none'>
                                        
                                         <tr rules='none' class='firstRow'>";
                foreach ($features as $v)
                {
                    $course_high_light .= "<td style='border-style: hidden; padding: 8px 12px 0px 0px; background-color: rgb(255, 255, 255); vertical-align: middle; color: rgb(255, 255, 255); word-break: break-all;' width='33.3%' valign='top'>
                                            <p style='background-color: rgb(86,180,48);padding: 15px 10px;word-break: normal;letter-spacing: 1.8px;'>
                                            <span style='color: rgb(255, 255, 255); font-size: 20px; letter-spacing: 1.8px; background-color: rgb(86, 180, 48);'>$v</span></p>
                                            </td>";
                }

                $course_high_light .="</tr>
</tbody></table><p><br></p><p><br></p></div>";
                $courseLearningContent = "<div id='courseLearningContent' ng-bind-html='courseDetailModel.courseLearningContent' ng-if='classPreviewData.class.courseLearningContent.length > 0' class='ng-binding ng-scope'><table style='width:100%;border-style: hidden' width='493' cellspacing='0' border='0'>
<tbody><tr class='firstRow'><td style='padding: 0px; word-break: break-all;' width='112'><p style='margin:0px 0 10px 18px;height: 35px;width: 8px;background-color: #56b430;float:left'><br>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p style='font-size: 28px;color: #4c4c4c;margin:0px 0 10px 15px;height: 35px;line-height:35px;float:left;'>学习内容</p><p style='font-size: 28px;color: #4c4c4c;margin:0px 0 10px 15px;height: 35px;line-height:35px;float:left;'><br>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p style='font-size: 28px;color: #4c4c4c;margin:0px 0 10px 15px;height: 35px;line-height:35px;float:left;'><br>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p></td></tr></tbody></table><table frame='void' style='font-size: 20px;border-collapse: separate;border:none;border-style: hidden;padding: 0 18px;width: 100%;margin-bottom: 0;table-layout: fixed;' width='493' cellspacing='0' border='0' align='center'>
 <tbody rules='none'><tr rules='none' class='firstRow'><td style='border-style: hidden;background-color: rgb(255,255,255);color: #fff;padding-top: 8px;' width='22' valign='top'><p style='width:22px;height:22px;background-color:rgb(252,206,47);'><br>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p></td><td style='border-style: hidden; background-color: rgb(255, 255, 255); padding: 5px 10px; vertical-align: middle; color: rgb(255, 255, 255); word-break: break-all;' valign='top'>
 <p style='color:#666666;letter-spacing: 1.8px;'>$value->course_content</p><p style='color:#666666;letter-spacing: 1.8px;'><br></p></td></tr></tbody></table></div>";
                $courseObservationStyle = "<div id='courseObservationStyle' ng-bind-html='courseDetailModel.courseObservationStyle' ng-if='classPreviewData.class.courseObservationStyle.length > 0' class='ng-binding ng-scope'>
<table style='width:100%;border-style: hidden' width='493' cellspacing='0' border='0'><tbody><tr class='firstRow'><td style='padding: 0px; word-break: break-all;' width='112'><p style='margin:0px 0 10px 18px;height: 35px;width: 8px;background-color: #56b430;float:left'><br>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p style='font-size: 28px;color: #4c4c4c;margin:0px 0 10px 15px;height: 35px;line-height:35px;float:left;'>学习流程</p></td></tr></tbody></table><table frame='void' style='font-size: 20px;border-collapse: separate;padding: 0 18px;border:  none;border-style: hidden;width: 100%;margin-bottom: 0;' width='493' cellspacing='0' border='0' align='center'><tbody rules='none'><tr rules='none' class='firstRow'><td style='text-align: center; border-style: hidden; padding: 10px 0px; white-space: nowrap; vertical-align: middle; color: rgb(255, 255, 255); word-break: break-all;' valign='top'>
 <img style='width: 100%;' src='https://www.ty-sx.com/libs/ueditor/upload/image/20181128/1543375177842095788.png' title=' alt='>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td></tr></tbody></table><p><br></p></div>";

                $courseConsultant = "<div id='courseConsultant' ng-bind-html='courseDetailModel.courseConsultant' ng-if='classPreviewData.class.courseConsultant.length > 0' class='ng-binding ng-scope'><table style='width:100%;border-style: hidden' cellspacing='0' border='0'><tbody><tr class='firstRow'><td style='padding: 0px; word-break: break-all;'><p style='margin:0px 0 10px 18px;height: 32px;width: 8px;background-color: #56b430;float:left'><br>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p style='font-size: 28px;color: #4c4c4c;margin:0px 0 10px 15px;height: 32px;line-height:32px;float:left;'>课程咨询</p></td></tr></tbody></table><table frame='void' style='font-size: 20px;border-collapse: separate;padding: 0 18px;border:  none;border-style: hidden;width: 100%;margin-bottom: 0;' cellspacing='0' border='0' align='center'><tbody rules='none'><tr rules='none' class='firstRow'>
 <td style='text-align: center; border-style: hidden; padding: 4px 12px 4px 0; white-space: nowrap; vertical-align: middle; color: rgb(255, 255, 255); word-break: break-all;' width='50%' valign='top'><p style='background-color:rgb(238,247,234);'><img style='width: 100%;' src='https://www.ty-sx.com/libs/ueditor/upload/image/20181128/1543374753245012386.png' title=' alt='>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p></td><td style='text-align: center; border-style: hidden; padding: 4px 0px 4px 12px; white-space: nowrap; vertical-align: middle; color: rgb(255, 255, 255); word-break: break-all;' width='50%' valign='top'><p style='background-color:rgb(238,247,234);'><img style='width: 100%;' src='https://www.ty-sx.com/libs/ueditor/upload/image/20181128/1543374775069088898.png' title=' alt='>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p></td></tr></tbody></table><p><br></p></div>";

                $courseWarmPrompt = "<div id='courseWarmPrompt' ng-bind-html='courseDetailModel.courseWarmPrompt' ng-if='classPreviewData.class.courseWarmPrompt.length > 0' class='ng-binding ng-scope'>
<table width='1063' cellspacing='0'><tbody><tr class='firstRow'><td style='padding: 0px; word-break: break-all;'>
<p style='margin-bottom: 10px; margin-left: 18px; height: 32px; width: 8px; background-color: rgb(86, 180, 48); float: left;'>
<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p style='margin-bottom: 10px; margin-left: 15px; font-size: 28px; color: rgb(76, 76, 76); height: 32px; line-height: 32px; float: left;'>温馨提示</p>
</td></tr></tbody></table><p style='margin-left: 30px; white-space: normal; font-size: 20px;'>1、讲义下载时间：开课前三天上传学生版讲义，可提前下载学习</p>
<p style='margin-left: 30px; white-space: normal; font-size: 20px;'>2、Classin下载地址：<a href='http://www.eeo.cn/' _src='http://www.eeo.cn' style='color: rgb(86, 180, 48); text-decoration-line: none;'>http://www.eeo.cn</a></p>
<p style='margin-left: 30px; white-space: normal; font-size: 20px;'><br></p>
<p><br></p></div>";

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
                    'courseInformation'=>  $courseInformation,//富文本 课程信息
                    'courseTeachersHighlight'=> $teacher_high_light,//富文本 师资介绍
                    'courseHighlight'=> $course_high_light,//富文本 课程特点
                    'courseLearningContent'=>$courseLearningContent,//富文本 课程内容
                    'courseObservationStyle'=> $courseObservationStyle,//富文本 观看方式
                    'courseConsultant'=>   $courseConsultant,//富文本
                    'courseWarmPrompt'=>  $courseWarmPrompt,//富文本 温馨提示
                    'siteName'=>urlencode($jigou),
                    'platform'=>'liveCourseConnect',
                    'timestamp'=>$date_time,
                    'maxNum'=>100,
                    'key'=>$key
                ];

                $res = Http::post($url,$arr);

                var_dump($res);
              
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
//                'args'=>json_encode($arr),
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
