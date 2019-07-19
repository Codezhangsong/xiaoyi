<?php

namespace App\Http\Controllers;

use App\Model\Activity;
use App\Model\Logs\TaskLog;
use App\Services\Utils\BaiduTj;
use App\Services\Utils\Des;
use App\Services\Utils\GaoDeMap;
use App\Services\Utils\Helps;
use App\Services\Utils\Http;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use PHPExcel_Worksheet_Drawing;
use Excel;

class TestController extends Controller
{
    public function test(Request $request) {

        $data ='13122799016';
        $key = 'tyxs9sx';
        $iv='11111111';
       
        $result = openssl_encrypt($data, 'DES-ECB', $key, OPENSSL_RAW_DATA);
       // var_dump($result);
        

      // $data = openssl_encrypt($input, 'des-ede3-cbc', $key, OPENSSL_RAW_DATA,$iv);
       // openssl_encrypt($data, 'des-ede3-cbc', self::KEY, OPENSSL_RAW_DATA, self::IV));
       //$sm = base64_encode($data);
     


       //var_dump(base64_encode($result));

        var_dump(bin2hex($result));
        exit;

        $userId = 113;

       $cacheName = 'deviceUUID:user'.$userId; 

       Redis::del($cacheName);
       exit;

        $m = Redis::del($cacheName);
       $m = Redis::get($cacheName);
        // var_dump(222222,$m);
        // exit;


        $deviceUUID = $this->getDeviceUUID(); 
        $timeout = 10; // 用户10十分钟无操作自动下线
        Redis::set($cacheName, $deviceUUID);
        Redis::expire($cacheName, $timeout);
        $m = Redis::get($cacheName);
        $ttl = Redis::TTL($cacheName);
        var_dump(123,$cacheName,$m,$ttl);
        var_dump(env('APP_ENV'));

//        Redis::get('11');
//        dd(123);
      //  return view('index');

    }

    public function getDeviceUUID(){

        $devicecode = md5('xiaoyi'.time());
        return $devicecode;
    }

    public  function registerUserDevice()
    {
       
        $cacheName = 'deviceUUID:user'.$userId; 
        $deviceUUID = $this->getDeviceUUID(); 
        $timeout = 600; // 用户10十分钟无操作自动下线
        Redis::set($cacheName, $deviceUUID);
        Redis::setTimeout($cacheName, $timeout);
    }


    public function getContent(Request $request)
    {
        $content = $request->input('html');

        $html = '<p>asfasfasf</p><p><img src="/uploads/ueditor/php/upload/image/20190510/1557452398101914.png" title="1557452398101914.png" alt="屏幕快照 2019-05-06 下午4.26.03.png"/></p><p><br/></p><p>asdasdasdasdasdsadflkjalsfdfilsdj<img src="/uploads/ueditor/php/upload/image/20190510/1557452408496596.png" title="1557452408496596.png" alt="屏幕快照 2019-05-04 上午9.31.14.png"/></p>"';
        $pattern_src = '/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/';
        $num1 = preg_match_all($pattern_src, $html, $match_src1);
        $arr_src1 = $match_src1[1];
        var_dump($arr_src1);
//        return $html;
    }

    public function get()
    {
        $courseName = '';
        $desc = '';
        $courseNum= '';
        $price = '';
        $startTime= '';
        $endTime = '';
        $class = '';
        $courseFeature = '';

        $excel_file_path = 'public\456.xlsx';
        Excel::load($excel_file_path, function($reader) use( &$res ) {
            $reader = $reader->getSheet(1);
            $res = $reader->toArray();
            $courseName = $res[1][1];
            $teacherInfo = $res[2][1];
            $price = $res[1][4];
            $courseNum = $res[1][6];
            $desc = $res[2][1];
            $class = $res[6][2];
            $courseFeature = $res[4];
            $courseObject = $res[7][2];
            $course_content = $res[9][1];

            $arr = [
                'course_name'=>$courseName,
                'desc'=>$desc,
                'course_content'=>$course_content,
                'course_obj'=>$courseObject,
                'course_feature'=>$courseFeature,
                'class'=>$class,
                'course_num'=>$courseNum,
                'price'=>$price,
                'teacher_info'=>$teacherInfo,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ];


        });

        $arr = [
          'course_name'=>$courseName,
          'desc'=>$desc,
        ];
    }

    public function api()
    {
        return view('api');
    }

    public function teacher(Request $request)
    {
        $url = $request->input('url');
        $res = Http::get($url,[]);
        if($res['code']==200)
            $arr = json_decode($res['data'],true);
        return response()->json($arr);
    }

    public function teacherAdd(Request $request)
    {
        $arr = [
            'returnCode'=>'1',
            'returnMessage'=>'添加成功',
            'returnData'=>[
                'teacherId'=>'123'
            ]
        ];
        $arr = json_encode($arr);
        return response()->json($arr);
    }

    public function CourseAdd()
    {
        $arr = [
            'returnCode'=>'1',
            'returnMessage'=>'添加成功',
            'returnData'=>[
                'classId'=>'123'
            ]
        ];
        $arr = json_encode($arr);
        return response()->json($arr);
    }

    public function LessonAdd()
    {
        $arr = [
            'returnCode'=>'1',
            'returnMessage'=>'添加成功',
            'returnData'=>[
                'lessonId'=>'123'
            ]
        ];
        $arr = json_encode($arr);
        return response()->json($arr);
    }

    public function CourseSearch()
    {
        $arr = [
            'returnCode'=>'1',
            'returnMessage'=>'添加成功',
            'returnData'=>[
                'name'=>'123',
                'code'=>'123',
                'coverImgLink'=>'123',
                'coverVid'=>'123',
                'enrollStartDate'=>'123',
                'enrollEndDate'=>'123',
                'expirationDuration'=>'123',
                'classHour'=>'123',
                'classType'=>'123',
                'maxNum'=>'123',
                'primeCost'=>'123',
                'cost'=>'123',
                'courseIntroduceImg'=>'123',
                'courseInformation'=>'123',
                'courseTeachersHighlight'=>'123',
                'courseHighlight'=>'123',
                'courseLearningContent'=>'123',
                'courseObservationStyle'=>'123',
                'courseConsultant'=>'123',
                'courseWarmPrompt'=>'123',
                'lessonDatas'=>[
                    [
                        'teacherId'=>'xxx',
                        'classId'=>'xxx',
                        'liveContent'=>'xxx',
                        'code'=>'xxx',
                        'liveStartDate'=>'xxx',
                        'liveEndDate'=>'xxx',
                        'lessonHour'=>'xxx',
                    ],
                    [
                        'teacherId'=>'xxx',
                        'classId'=>'xxx',
                        'liveContent'=>'xxx',
                        'code'=>'xxx',
                        'liveStartDate'=>'xxx',
                        'liveEndDate'=>'xxx',
                        'lessonHour'=>'xxx',
                    ],

                ],

            ]
        ];
        $arr = json_encode($arr);
        return response()->json($arr);
    }

    public function baiDuTjData()
    {
        try{
            $url = config('baidutj.url');
            $headers['type'] = config('baidutj.type');
            $headers['username'] = config('baidutj.username');
            $headers['password'] = config('baidutj.password');
            $headers['token'] = config('baidutj.token');
            $body['siteId'] = config('baidutj.siteId');
            $body['method'] = 'overview/getCommonTrackRpt';
            $body['s_time'] = Carbon::now()->subYear(1)->format('Ymd');
            $body['e_time'] = Carbon::now()->format('Ymd');
//            $body['metrics'] = 'pv_count,visitor_count,exit_ratio';
            $body['gran'] = 'month';
            $body['max_results']   = 0;
            $vData = BaiduTj::getData($url, $headers, $body);
            dd($vData);
            if(isset($vData['body']['data'][0]['result']['visitPage']['items']) && !empty($vData['body']['data'][0]['result']['visitPage']['items']))
            {
                $result = $vData['body']['data'][0]['result']['visitPage']['items'];

                foreach ($result as $key =>$value)
                {
                    $arr[$value[0]] = $value[1];
                }
            }
            $res = Activity::where('is_del',1)->get();

            if($res->count()){
                foreach ($res as $key=>&$value)
                {

                    if(isset($arr[$value->url]))
                    {
                        $value->PV = $arr[$value->url];
                        $value->updated_at = Carbon::now();
                        $value->save();
                    }
                }
            }

//            if(!$id)
//                throw new \Exception('定时任务插入失败');
//            TaskLog::insert([
//                'task_name'=>$this->signature,
//                'result'=>$id,
//                'error'=>null,
//                'created_at'=>Carbon::now(),
//                'updated_at'=>Carbon::now(),
//            ]);
        }catch (\Exception $e){
            dd($e->getMessage());
//            TaskLog::insert([
//                'task_name'=>$this->signature,
//                'result'=>'fail',
//                'error'=>$e->getMessage(),
//                'created_at'=>Carbon::now(),
//                'updated_at'=>Carbon::now(),
//            ]);
        }
    }

    public function baiDuTjList()
    {
        $url = config('baidutj.url');
        $url = 'https://api.baidu.com/json/tongji/v1/ReportService/getSiteList';
        $headers['type'] = config('baidutj.type');
        $headers['username'] = config('baidutj.username');
        $headers['password'] = config('baidutj.password');
        $headers['token'] = config('baidutj.token');

        $vData = BaiduTj::getList($url, $headers);
        dd($vData);
        $visitor = 0;
        if ($vData && $vData['header']['succ'] ==1) {
            $visitor = $vData['body']['data'][0]['result']['sum'][0][0];
        }
    }

    public function teacherTest()
    {
        $mobile = '13122799016';

        $parameter = [
            'functionCode' => 'addTeacher',
            'userId' => $mobile,
            'siteName' => '添翼申学',
            'orient' => '是',
            'note' => '是',
            'platform'=>'liveCourseConnect',
            'timestamp'=>date('YmdHis',time()),
        ];
        $sign = Helps::createSignature($parameter);
//        var_dump($sign);
//        $des = new Des();
//        $des_mobile = $des->encrypt($mobile);
        $des_m =
//        $des_mobile = Helps::des_ecb_encrypt($mobile,$key = 'tyxs9sx');

        $arr = [
            'functionCode' => 'addTeacher',
            'userId' => 'a6c4f2451a964475344ef5f1faebf7ef',
            'siteName' => urlencode('添翼申学'),
            'orient' => urlencode('是'),
            'note' => urlencode('是'),
            'platform'=>'liveCourseConnect',
            'timestamp'=>date('YmdHis',time()),
            'key'=>$sign
        ];

        var_dump($parameter);

        var_dump($arr);
        $url = 'http://08ff2ch7721d.ct-edu.com.cn/thirdparty/liveCourseMaintenance/addTeacher';
        $res = Http::post($url,$arr);
        dd($res);
    }

    public function courseTest()
    {
        $parameter = [
            'functionCode' => 'addLiveCourse',
            'name' => 'test',
            'code' => md5('test4'),
            'coverImgLink' => 'test',
            'enrollStartDate' => date('YmdHis',time()),
            'enrollEndDate' => date('YmdHis',time()),
            'classHour'=>  6,
            'maxNum'=>100,
            'classType'=>   '素质教育',
            'primeCost'=>  100000,
            'cost'=>100000,
            'siteName'=>'添翼申学',
            'platform'=>'liveCourseConnect' ,
            'timestamp'=>Carbon::now()->format('YmdHis'),
        ];
        $key = Helps::createSignature($parameter);
        $arr = [
            'functionCode' => 'addLiveCourse',
            'name' => 'test',
            'code' => md5('test4'),
            'coverImgLink' => 'test',
            'enrollStartDate' => date('YmdHis',time()),
            'enrollEndDate' => date('YmdHis',time()),
            'classHour'=>  6,
            'classType'=>   urlencode('素质教育'),
            'primeCost'=>  100000,
            'cost'=>100000,
            'courseIntroduceImg'=> '得分还是得分', //富文本
            'courseInformation'=>  '得分还是得分' ,//富文本
            'courseTeachersHighlight'=> '得分还是得分',//富文本
            'courseHighlight'=> '得分还是得分',//富文本
            'courseLearningContent'=>'得分还是得分',//富文本
            'courseObservationStyle'=> '直播',//富文本
            'courseConsultant'=>   '得分还是得分',//富文本
            'courseWarmPrompt'=>  '得分还是得分' ,//富文本
            'siteName'=>urlencode('添翼申学'),
            'platform'=>'liveCourseConnect' ,
            'timestamp'=>date('YmdHis',time()),
            'maxNum'=>100,
            'key'=>$key
        ];

        $url = 'http://08ff2ch7721d.ct-edu.com.cn/thirdparty/liveCourseMaintenance/addLiveClass';
        var_dump($arr);
        $res = Http::post($url,$arr);

        dd($res);
    }

    public function courseCheck()
    {
        $parameter = [
            'functionCode' => 'getClassDetail',
            'classId' => '8a8a0d4b6a8bfd47016a8c17d7e303d0',
            'platform'=>  'liveCourseConnect' ,
            'timestamp'=>  date('YmdHis',time()),
        ];
        $key = Helps::createSignature($parameter);
        $arr = [
            'functionCode' => 'getClassDetail',
            'classId' => '8a8a0d4b6a8bfd47016a8c17d7e303d0',
            'platform'=>  'liveCourseConnect' ,
            'timestamp'=>  date('YmdHis',time()),
            'key'=>$key
        ];
        $url ='http://08ff2ch7721d.ct-edu.com.cn/thirdparty/liveCourseMaintenance/getClassDetail';
        $res = Http::post($url,$arr);

        dd($res);
    }

    public function lesson()
    {
        $now = Carbon::now()->format('YmdHis');
//        $start_date = Carbon::now()->subDays(-10)->format('YmdHis');
//        $end_date = Carbon::now()->subDays(-11)->format('YmdHis');
//         = '20190625104159';
//        $time = '20190628104159';
        $start_date='20190506173000';
        $end_date='20190506183000';

        $parameter = [
            'functionCode' => 'addLesson',
            'teacherId' => '8a8a0d4b6a8bfd47016a8c1678c103be',
            'classId' => '8a8a0d4b6a8bfd47016a8c17d7e303d0',
            'liveContent'=>'test',
            'code' => md5($now),
            'liveStartDate' => $start_date,
            'liveEndDate' => $end_date,
            'lessonHour'=>1.0,
            'liveManNumber'=>15,
            'platform'=>'liveCourseConnect',
            'timestamp'=> $now,
        ];
        $key = Helps::createSignature($parameter);
        $arr = [
            'functionCode' => 'addLesson',
            'teacherId' => '8a8a0d4b6a8bfd47016a8c1678c103be',
            'classId' => '8a8a0d4b6a8bfd47016a8c17d7e303d0',
            'liveContent'=>'test',
            'code' => md5($now),
            'liveStartDate' => $start_date,
            'liveEndDate' => $end_date,
            'lessonHour'=>1.0,
            'liveManNumber'=>15,
            'platform'=>'liveCourseConnect',
            'timestamp'=> $now,
            'key'=>$key
        ];
        $url ='http://08ff2ch7721d.ct-edu.com.cn/thirdparty/liveCourseMaintenance/addLesson';
        var_dump($parameter);

        var_dump($arr);
        $res = Http::post($url,$arr);

        dd($res);
    }

    public function act_stat()
    {
        dd(123);
    }

}
