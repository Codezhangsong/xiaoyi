<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Model\ActivityClass;
use App\Model\ActivityRecord;
use App\Model\Course;
use App\Model\Level;
use App\Model\Orgs;
use App\Model\Tags;
use Carbon\Carbon;

$factory->define(App\Model\Students::class, function (Faker\Generator $faker) {
    $start_date = Carbon::create('2019',rand(5,6),rand(1,30),rand(0,24),rand(1,60));
    $date = Carbon::create('2019','05',rand(1,30));

    $province = [
        '北京','上海','辽宁省','湖南省','黑龙江省','江苏省','浙江省',
        '四川省',
        '湖北省',
        '广东省',
        '广西省',
        '山东省',
        '陕西省',
    ];

    $street = [
        '第1街道',
        '第2街道',
        '第3街道',
        '第4街道',
        '第5街道',
        '第6街道',
        '第7街道',
        '第8街道',
        '第9街道',
        '第10街道',
    ];
    return [
        'name' => $faker->name,
        'parent_name' => $faker->name,
        'parent_mobile' => $faker->phoneNumber,
        'gender' => rand(1,2),
        'age' => rand(0,17),
        'birthday' => $faker->date(),
        'school' => '第一中学',
        'province' => $province[rand(0,12)],
        'city' => $faker->city,
        'region' => array_rand(['浦东','徐汇','虹口','黄浦','闵行']),
        'street' => $street[rand(0,9)],
        'origin' => 'web',
        'lat'=>'121.'.rand(10,76),
        'lng'=>rand(30,31).'.'.rand(10,99),
        'intention' => '数学',
        'reg_date' =>  $date,
        'created_at' => $start_date,
        'updated_at' => $start_date,
    ];
});

//
$factory->define(App\Model\Parents::class, function (Faker\Generator $faker) {
    $random = rand(1,21);
    $month = rand(1,9);
    $after_week = $random + 7;
    $start_date = '2019-0'.$month.'-'.$random;
    $birthday = $faker->date();
    $start_date = Carbon::create('2019',rand(1,12),rand(1,30),rand(0,24),rand(1,60));
    $date = Carbon::create('2019','05','11');
    return [
        'name' => $faker->name,
        'mobile' => $faker->phoneNumber,
        'gender' => rand(1,2),
        'birthday' => $birthday,
        'age' => \App\Services\Utils\Helps::birthdayTransLate($birthday),
        'level_id' => 1,
        'level' => '青铜',
        'tag_id' => 1,
        'tag_name' => '留学',
        'province' => '上海市',
        'city' => $faker->city,
        'region' => array_rand(['浦东','徐汇','虹口','黄浦','闵行']),
        'street' => $faker->streetAddress,
        'occupation' => '开发',
        'use_flag' => rand(1,0),
        'password'=>md5(rand(100,1)),
        'reg_date' =>  $date,
        'created_at' => $start_date,
        'updated_at' => $start_date,
    ];
});
//
//
$factory->define(App\Model\Course::class, function (Faker\Generator $faker) {
    $random = rand(1,21);
    $after_week = $random + 7;
    $start_date = '2020-04-'.$random;
    $end_date = '2020-04-'.$after_week;
//
//    $course_id = \Illuminate\Support\Facades\DB::table('course')->insertGetId([
//        'code'=>md5(time()),
//        'course_name' => $faker->name.'老师暑假辅导课',
//        'teacher_name'=>'方元',
//        'teacher_id'=>'8a8a0d4b6a483deb016a4931423b037a',
//        'class_id'=>'8a8a0d4b6a483deb016a496298e00409',
//        'account'=>13122799016,
//        'teacher_info'=>'教师信息 XXX',
//        'desc'=>'课程描述xxx',
//        'class_type'=>'素质教育',
//        'cover_img'=>'http://tech.southcn.com/t/attachment/20180821/20226770/96a5cb4621424afd81e1.jpg',//封面图片
//        'course_introduce_img'=>'http://tech.southcn.com/t/attachment/20180821/20226770/96a5cb4621424afd81e1.jpg',//课程主图
//        'course_num'=>1,
//        'class_hour'=>1,
//        'course_consultant'=>'暂无',
//        'course_prompt'=>'暂无',
//        'course_obj'=>'考试得分',
//        'course_feature'=>'得分效率',
//        'course_content'=>'得分还是得分',
//        'class'=>'一年级',
//        'price'=>10,
//        'PV'=>rand(1,10000),
//        'UV'=>rand(1,10000),
//        'status'=>1,
//        'start_date'=>'2020-12-11',
//        'end_date'=>'2020-12-29',
//        'total_BR'=>round(rand(0,0.3),2),
//        'updated_at' => \Carbon\Carbon::now(),
//        'created_at' => \Carbon\Carbon::now(),
//        'reg_date' => $start_date,
//    ]);
//
//
//    \Illuminate\Support\Facades\DB::table('course_detail')->insert([
//        'course_id'=>$course_id,
//        'lesson_name'=>'测试课程',
//        'lesson_num'=>1,
//        'code'=>md5('8a8a0d4b6a4cff0e016a4d3ad40800b0'),
//        'lesson_id'=>'8a8a0d4b6a4cff0e016a4d3ad40800b0',
//        'lesson_date'=>'2020-12-11',
//        'start_time'=>'08:00:00',
//        'end_time'=>'09:00:00',
//    ]);
//
    $random_date = Carbon::create('2019',5,12,rand(0,24));

    return [
        'code'=>md5(time()),
        'course_name' => $faker->name.'老师暑假辅导课',
        'teacher_name'=>'方元',
        'teacher_id'=>'8a8a0d4b6a483deb016a4931423b037a',
        'class_id'=>'8a8a0d4b6a483deb016a496298e00409',
        'account'=>13122799016,
        'teacher_info'=>'教师信息 XXX',
        'desc'=>'课程描述xxx',
        'class_type'=>'素质教育',
        'cover_img'=>'http://tech.southcn.com/t/attachment/20180821/20226770/96a5cb4621424afd81e1.jpg',//封面图片
        'course_introduce_img'=>'http://tech.southcn.com/t/attachment/20180821/20226770/96a5cb4621424afd81e1.jpg',//课程主图
        'course_num'=>1,
        'class_hour'=>1,
        'course_consultant'=>'暂无',
        'course_prompt'=>'暂无',
        'course_obj'=>'考试得分',
        'course_feature'=>'得分效率',
        'course_content'=>'得分还是得分',
        'class'=>'一年级',
        'price'=>10,
        'PV'=>rand(1,10),
        'UV'=>rand(1,10),
        'status'=>rand(1,4),
        'start_date'=>$start_date,
        'end_date'=>$end_date,
        'total_BR'=>round(rand(0,0.3),2),
        'updated_at' => $random_date,
        'created_at' => $random_date,
        'reg_date' => $start_date,
    ];


});
//
$factory->define(App\Model\Activity::class, function (Faker\Generator $faker) {
    $random = rand(1,21);
    $after_week = $random + 7;
//    $year = rand(8,9);
//    $start_date = '2019-05-'.rand() $.' '..rand(10,23).':'.rand(10,59).':'.'00';

//    $end_date = '2019-04-'.$after_week;

    $start_date = Carbon::create('2019',rand(1,12),rand(1,30));
    return [
        'name' => $faker->name.'老师暑期兴趣活动',
        'online' => rand(1,2),
        'class' => '素质教育',
        'classId' => 1,
        'type'=>1,
        'channel'=>'channel',
        'channel_id'=>1,
        'PV'=>rand(1,10),
        'UV'=>rand(1,10),
        'stay_minutes'=>rand(1,5),
        'bounce_rate'=>0.1,
        'student_num'=>rand(1,100),
        'parent_num'=>rand(1,100),
        'creator'=>'sys',
        'updated_at' => $start_date,
        'created_at' => $start_date,
    ];
});
//
//$factory->define(App\Model\ActivityClass::class, function (Faker\Generator $faker) {
//    $random = rand(1,21);
//    $after_week = $random + 7;
//    $start_date = '2019-04-'.$random;
//    $end_date = '2019-04-'.$after_week;
//    return [
//        'class_name' => '素质教育',
//        'updated_at' => $start_date. '00:00:00',
//        'created_at' => $start_date. '00:00:00',
//    ];
//});
//
//$factory->define(App\Model\Channel::class, function (Faker\Generator $faker) {
//    $random = rand(1,21);
//    $start_date = '2019-04-'.$random;
//    return [
//        'channel' => 'channel_1',
//        'comment'=>'1111',
//        'updated_at' => $start_date. '00:00:00',
//        'created_at' => $start_date. '00:00:00',
//    ];
//});
//
//$factory->define(App\Model\Tags::class, function (Faker\Generator $faker) {
//    return [
//        'tag_name' => '标签'.rand(1,3),
//        'updated_at' => \Carbon\Carbon::now(),
//        'created_at' => \Carbon\Carbon::now(),
//    ];
//});
//
//$factory->define(App\Model\Level::class, function (Faker\Generator $faker) {
//    return [
//        'level_name' => '等级'.rand(1,3),
//        'updated_at' => \Carbon\Carbon::now(),
//        'created_at' => \Carbon\Carbon::now(),
//    ];
//});
//
//$factory->define(App\Model\MessageType::class, function (Faker\Generator $faker) {
//    return [
//        'type_name' => '消息类型'.rand(1,3),
//        'creator'=>'sys',
//        'updated_at' => \Carbon\Carbon::now(),
//        'created_at' => \Carbon\Carbon::now(),
//    ];
//});
//
//$factory->define(App\Model\Message::class, function (Faker\Generator $faker) {
//    return [
//        'title' => '消息'.rand(1,100),
//        'type_id' => rand(1,3),
//        'type_name' => '应用消息',
//        'status' => 1,
//        'updated_at' => \Carbon\Carbon::now(),
//        'created_at' => \Carbon\Carbon::now(),
//    ];
//});
//
//$factory->define(App\Model\Orgs::class, function (Faker\Generator $faker) {
//    return [
//        'org_name' => $faker->firstNameMale.'培训机构',
//        'linkman' => $faker->firstNameMale,
//        'mobile'=>13122799018,
//        'address'=>'东方路 1357号',
//        'distinction'=>'上海',
//        'desc'=>'略',
//        'pic_url'=>'wwww.sss.com?asjkdnahnafi.jpg',
//        'created_at' => \Carbon\Carbon::now(),
//        'updated_at' => \Carbon\Carbon::now(),
//    ];
//});
//
//$factory->define(App\Model\ActivityRecord::class, function (Faker\Generator $faker) {
//    $datetime = \Carbon\Carbon::now()->subDays(rand(1,30));
//    return [
//        'name' => $faker->firstNameMale,
//        'mobile'=>13122799018,
//        'comment'=>'报名参加',
//        'gender'=>1,
//        'channel_id'=>1,
//        'activity_id'=>1,
//        'created_at' => $datetime,
//        'updated_at' => $datetime,
//    ];
//});
