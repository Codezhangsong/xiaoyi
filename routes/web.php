<?php

use App\Http\Middleware\WebToken;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::any('export/excel', 'ExportController@export');

//微信公众号
Route::any('wechat/token', 'WeChat\WeChatController@getToken');

Route::any('wechat/send/tag', 'WeChat\WeChatController@sendAll');

Route::any('wechat/open/send', 'WeChat\WeChatController@sendByOpenId');

Route::any('wechat/image/url', 'WeChat\WeChatController@getImageUrl');

Route::any('wechat/get/content', 'WeChat\WeChatController@getContent');
//查询openid
Route::any('wechat/search/openid', 'WeChat\WeChatController@searchOpenId');

Route::any('wechat/upload/image', 'WeChat\WeChatController@uploadImage');
Route::any('wechat/upload/video', 'WgetTokeneChat\WeChatController@uploadVideo');
Route::any('wechat/upload/thumb', 'WeChat\WeChatController@uploadThumb');
Route::any('wechat/upload/news', 'WeChat\WeChatController@uploadNews');
Route::any('wechat/get/tag', 'WeChat\WeChatController@getTag');
Route::any('wechat/send/tag', 'WeChat\WeChatController@sendAll');


Route::any('test/act', 'TestController@act_stat');

Route::get('static/{id}.html/{string}', function ($id,$string) {
    \View::addExtension('html', 'php');
    return view($id);
});

Route::any('org/info', 'OrgController@info');


Route::get('tj/index', function () {
    return view('index');
});

Route::any('activity/get/content', 'ActivityController@getContent');


Route::group(['middleware' => 'login'], function () {
    Route::middleware(['token'])->group(function () {
//活动
        Route::any('activity/search', 'ActivityController@search');
        Route::any('activity/add', 'ActivityController@add');
        Route::any('activity/get', 'ActivityController@get');
        Route::any('activity/edit', 'ActivityController@edit');
        Route::any('activity/del', 'ActivityController@del');
        Route::any('activity/generate', 'ActivityController@generate');
        Route::any('activity/get', 'ActivityController@get');
//活动类目
        Route::any('activity/class/search', 'ActivityClassController@search');
        Route::any('activity/class/add', 'ActivityClassController@add');
        Route::any('activity/class/edit', 'ActivityClassController@edit');
        Route::any('activity/class/del', 'ActivityClassController@del');

//活动记录表
        Route::any('activity/record/test', 'Activity\ActivityRecordController@test');
        Route::any('activity/record/add', 'Activity\ActivityRecordController@add');
        Route::any('activity/record/edit', 'Activity\ActivityRecordController@edit');
        Route::any('activity/record/del', 'Activity\ActivityRecordController@del');
        Route::any('activity/record/search', 'Activity\ActivityRecordController@search');

//学生
        Route::any('student/search', 'StudentController@search');
        Route::any('student/add', 'StudentController@add');
        Route::any('student/edit', 'StudentController@edit');
        Route::any('student/del', 'StudentController@del');
//家长
        Route::any('parent/search', 'ParentsController@search');
        Route::any('parent/add', 'ParentsController@add');
        Route::any('parent/edit', 'ParentsController@edit');
        Route::any('parent/del', 'ParentsController@del');
//标签
        Route::any('tag/search', 'TagController@search');
        Route::any('tag/add', 'TagController@add');
        Route::any('tag/edit', 'TagController@edit');
        Route::any('tag/del', 'TagController@del');
//等级
        Route::any('level/search', 'LevelController@search');
        Route::any('level/add', 'LevelController@add');
        Route::any('level/edit', 'LevelController@edit');
        Route::any('level/del', 'LevelController@del');

//课程
        Route::any('course/search', 'CourseController@search');
        Route::any('course/detail', 'CourseController@get');

//消息
        Route::any('message/search', 'MessageController@search');
        Route::any('message/add', 'MessageController@add');
        Route::any('message/edit', 'MessageController@edit');
        Route::any('message/del', 'MessageController@del');
        Route::any('message/type/search', 'MessageTypeController@search');
        Route::any('message/type/add', 'MessageTypeController@add');
        Route::any('message/type/edit', 'MessageTypeController@edit');
        Route::any('message/type/del', 'MessageTypeController@del');

//统计 家长
        Route::any('stat/parent/search', 'Stat\StatParentController@search');
        Route::any('stat/parent/stat', 'Stat\StatParentController@stat');
        Route::any('stat/parent/trend', 'Stat\StatParentController@increaseTrend');
//统计 课程
        Route::any('stat/course/trend', 'Stat\StatCourseController@trend');
        Route::any('stat/course/index', 'Stat\StatCourseController@index');
        Route::any('stat/increase/trend', 'Stat\StatCourseController@increaseTrend');

//统计 学生
        Route::any('stat/student/trend', 'Stat\StatStudentController@trend');
        Route::any('stat/student/index', 'Stat\StatStudentController@index');
//统计 活动
        Route::any('stat/activity/trend', 'Stat\StatActivityController@trend');
        Route::any('stat/activity/index', 'Stat\StatActivityController@index');


//机构端首页
        Route::any('index', 'IndexController@index');
        Route::any('index/activity/data', 'IndexController@getActivityData');
        Route::any('index/activity/detail', 'IndexController@getActivityDetail');

//后台首页
        Route::group(['prefix' => 'admin'], function () {
            Route::any('index', 'Admin\IndexController@index');
        });


//统计
        Route::group(['prefix' => 'article'], function () {
            Route::any('search', 'ArticleController@search');
            Route::any('add', 'ArticleController@add');
            Route::any('edit', 'ArticleController@edit');
            Route::any('del', 'ArticleController@del');

        });
//渠道
        Route::group(['prefix' => 'channel'], function () {
            Route::any('search', 'Channel\ChannelController@search');
            Route::any('add', 'Channel\ChannelController@add');
            Route::any('edit', 'Channel\ChannelController@edit');
            Route::any('del', 'Channel\ChannelController@del');

        });
    });

});

//机构
Route::any('org/search', 'OrgController@search');
Route::any('org/add', 'OrgController@add');
Route::any('org/edit', 'OrgController@edit');
Route::any('org/editpwd', 'OrgController@editpwd');
Route::any('org/del', 'OrgController@del');
Route::any('org/login', 'OrgController@check');
Route::any('org/logout', 'OrgController@logout');
Route::any('org/install', 'OrgController@install');
Route::any('org/checklogin', 'OrgController@checklogin');

Route::any('course/read', 'CourseController@readExcel');
Route::any('remote/add/teacher', 'CourseController@remoteAddTeacher');
Route::any('remote/add/course', 'CourseController@remoteAddCourse');
Route::any('upload/course', 'CourseController@uploadCourse');
Route::any('course/download/{type}', 'CourseController@download');
Route::any('course/audit/', 'CourseController@audit');

//测试路由
Route::any('test/get', 'TestController@get');
Route::any('test', 'TestController@test');
Route::any('teacher', 'TestController@teacherTest');
Route::any('course', 'TestController@courseTest');
Route::any('course/check', 'TestController@courseCheck');
Route::any('lesson', 'TestController@lesson');
Route::any('tj/data', 'TestController@baiDuTjData');
Route::any('tj/list', 'TestController@baiDuTjList');

//上传测试
Route::any('upload/test', 'Components\UploadController@test');
Route::any('upload/upload', 'Components\UploadController@upload');
Route::any('upload/excel', 'Components\UploadController@uploadExcel');

Route::any('gao/search', 'TestController@gaode');

Route::any('map/search', 'Map\MapController@search');

Route::any('img', 'Image\ImageController@get');

Route::any('location/province', 'Location\AddressController@getProvince');
Route::any('location/city', 'Location\AddressController@getCity');
Route::any('location/area', 'Location\AddressController@getArea');
Route::any('location/street', 'Location\AddressController@getStreet');

Route::any('adminaccount/add', 'AdminAccountController@add');
Route::any('adminaccount/check', 'AdminAccountController@check');
Route::any('adminaccount/logout', 'AdminAccountController@logout');

//    考试报名
Route::middleware(['web'])->group(function () {
    Route::any('exam/search', 'Exam\ExamController@search');
    Route::any('exam/add', 'Exam\ExamController@add');
    Route::any('exam/edit', 'Exam\ExamController@edit');
    Route::any('exam/editAdmin', 'Exam\ExamController@editAdmin');
    Route::any('exam/login', 'Exam\ExamController@login');
    Route::any('exam/sms', 'Exam\ExamController@sms');

    //获取学校 + 班级数据
    Route::any('school/search', 'Exam\SchoolController@search');
    Route::any('school/add', 'Exam\SchoolController@add');
});


