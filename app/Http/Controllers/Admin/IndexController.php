<?php

namespace App\Http\Controllers\Admin;

use App\Model\Activity;
use App\Model\Course;
use App\Model\Orgs;
use App\Model\Parents;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{

    public function index()
    {
        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $lastmonth = Carbon::now()->subDays(30)->format('m');

        $org_total = Orgs::where('is_del',1)->count(); //机构总数
        $increase_org = Orgs::where([['is_del','=',1],
            ['created_at','>=',$yesterday.'00:00:00'],
        ])->count(); //新增机构总数

        $course_total = Orgs::where('is_del',1)->count(); //课程总数
        //待审核课程数
        $audit_count = Course::where('is_del',1)->where('status',1)->count();
        //未通过课程数
        $un_pass = Course::where('is_del',1)->where('status',2)->count();
        //已下架课程数
        $off_course = Course::where('is_del',1)->where('status',3)->count();
        //已上架课程数
        $online_course = Course::where('is_del',1)->where('status',4)->count();

        //粉丝总数
        $folloers_total = Parents::where('is_del',1)->count();
        //今日新增
        $increase_followers = Parents::where([
            ['is_del','=',1],
            ['created_at','>=',$yesterday.'00:00:00'],
        ])->count(); //新增粉丝总数
        //昨日新增
        $yesterday_follower = Parents::where('is_del',1)->where('reg_date',$yesterday)->count();
        //本月新增
        $month_follower = Parents::where('is_del',1)->where([
            ['reg_date','>=',$lastmonth],
            ['reg_date','<=',$today],
        ])->count();

        $activity_total = Activity::where('is_del',1)->count(); //活动总数

        $increase_activity = Activity::where([['is_del','=',1],
            ['created_at','>=',$yesterday.'00:00:00'],
        ])->count(); //今日新增活动数


        $activity_online_type = DB::table('activity')->select(DB::raw('COUNT(id) as num , online'))->where([
            ['is_del','=',1],
        ])->groupBy('online')->get();

        $activity_class = DB::table('activity')->select(DB::raw('COUNT(id) as num , class'))->where([
            ['is_del','=',1],
        ])->groupBy('class')->get();

        $follower_top = DB::table('course')->where('is_del',1)->select('course_name','UV','PV')->limit(10)->orderBy('UV','desc')->get();

        $activity_top = DB::table('activity')->where('is_del',1)->select('name','UV','PV')->limit(10)->orderBy('UV','desc')->get();

        return response()->json([
            'code'=>200,
            'data'=>[
                'general_info'=>[
                    'total_org'=>$org_total,//总机构数
                    'org_increase'=>$increase_org,//新增机构数
                    'course_total'=>$course_total,//课程总数
                    'online_course'=>$online_course,//已上架课程数
                    'total_parents'=>$folloers_total,//粉丝总数
                    'increase_parents'=>$increase_followers,//今日新增粉丝数
                    'activity_total'=>$activity_total,//活动总数
                    'increase_activity'=>$increase_activity,//新增活动总数
                ],
                'course_general'=>[
                    'audit_course'=>$audit_count,//待审核课程，
                    'not_pass_course'=>$un_pass,//未通过课程，
                    'offline'=>$off_course,//已下线课程，
                    'online'=>$online_course,//已上线课程，
                    'total'=>$course_total,//全部，
                ],
                'followers_general'=>[
                    'today_increase'=>$increase_followers, //今日新增粉丝数
                    'yesterday_increase'=>$yesterday_follower,//昨日新增
                    'present_month_increase'=>$month_follower,//本月新增
                    'total_followers'=>$folloers_total,//粉丝总数
                ],
                'activity_general'=>[
                    'online_activity'=>$activity_online_type,
                    'activity_class'=>$activity_class,
                ],
                'top'=>[
                    'marketing_top'=>$activity_top,
                    'followers_top'=>$follower_top,
                ]
            ],
        ]);
    }

}
