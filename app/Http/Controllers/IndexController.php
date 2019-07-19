<?php

namespace App\Http\Controllers;

use App\Model\Activity;
use App\Model\Course;
use App\Model\Orgs;
use App\Model\Parents;
use App\Model\Students;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class IndexController extends Controller
{
    public function index()
    {
        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $last_month = Carbon::now()->subDays(30)->format('m');

        //家长总数
        $folloers_total = Parents::where('is_del',1)->where('org_code',ORGCODE)->count();

        //学生总数
        $total_students = Students::where('is_del',1)->where('org_code',ORGCODE)->count();
        //新增学生数
        $increase_students = Students::where([
            ['is_del','=',1],
            ['org_code','=',ORGCODE],
            ['created_at','>=',$yesterday.'00:00:00'],
        ])->count(); //新增粉丝总数

        //课程总数
        $course_total = Course::where('is_del',1)->where('org_code',ORGCODE)->count();
        //待审核课程数
        $audit_count = Course::where('is_del',1)->where('status',1)->where('org_code',ORGCODE)->count();
        //未通过课程数
        $un_pass = Course::where('is_del',1)->where('status',5)->where('org_code',ORGCODE)->count();
        //已上架课程数
        $online_course = Course::where('is_del',1)->where('status',4)->where('org_code',ORGCODE)->count();
        //活动总数
        $activity_total = Activity::where('is_del',1)->where('org_code',ORGCODE)->count();

        //今日新增活动数
        $increase_activity = Activity::where([['is_del','=',1],
            ['created_at','>=',$yesterday.'00:00:00'],
            ['org_code','=',ORGCODE],
        ])->count();

        //粉丝数据
        $increase_followers = Parents::where([
            ['is_del','=',1],
            ['created_at','>=',$yesterday.'00:00:00'],
            ['org_code','=',ORGCODE],
        ])->count(); //新增粉丝总数
        //待审核活动
        $audit_activity = Activity::where([
            ['is_del','=',1],
            ['status','=',1],
            ['org_code','=',ORGCODE],
        ])->count();
        //已下架活动
        $offline_activity = Activity::where([
            ['is_del','=',1],
            ['status','=',4],
            ['org_code','=',ORGCODE],
        ])->count();
        return response()->json([
            'code'=>200,
            'data'=>[
                'general_info'=>[
                    'total_parents'=>$folloers_total,//家长总数
                    'increase_parents'=>$increase_followers,//今日新增粉丝数
                    'total_students'=>$total_students,//学生总数
                    'increase_students'=>$increase_students,//今日新增学生数
                    'course_total'=>$course_total,//课程总数
                    'activity_total'=>$activity_total,//活动总数
                    'increase_activity'=>$increase_activity,//新增活动总数
                ],
                'course_general'=>[
                    'audit_course'=>$audit_count,//待审核课程，
                    'not_pass_course'=>$un_pass,//未通    过课程，
                    'online'=>$online_course,//已上线课程，
                    'total'=>$course_total,//待审核课程，
                ],
                'activity_general'=>[
                    'audit_activity'=>$audit_activity,//待审核
                    'offline_activity'=>$offline_activity,//下架
                    'online_course'=>$online_course,//已上架

                ],
            ],
        ]);
    }

    //机构端活动数据
    public function getActivityData(Request $request)
    {
        try{
            $dateType = $request->input('dateType',4);
            switch ($dateType)
            {
                case 1:
                    $where = [
                        ['is_del','=',1],
                        ['created_at','>=',Carbon::today()],
                        ['created_at','<=',Carbon::now()],
                        ['is_del','=',1],
                    ];
                    break;
                case 2:
                    $where = [
                        ['is_del','=',1],
                        ['created_at','>=',Carbon::yesterday()],
                        ['created_at','<=',Carbon::today()],
                        ['is_del','=',1],
                    ];
                    break;
                case 3:
                    $where = [
                        ['is_del','=',1],
                        ['created_at','>=',Carbon::today()->subDays(7)],
                        ['created_at','<=',Carbon::today()],
                        ['is_del','=',1],
                    ];
                    break;

                case 4:
                    $where = [
                        ['is_del','=',1],
                        ['created_at','>=',Carbon::today()->subDays(30)],
                        ['created_at','<=',Carbon::today()],
                        ['is_del','=',1],
                    ];
                    break;
                default:
                    $where = [
                        ['is_del','=',1],
                        ['created_at','>=',Carbon::today()],
                        ['created_at','<=',Carbon::now()],
                        ['is_del','=',1],
                    ];
            }

            $where[] = ['org_code','=',ORGCODE];
            $activity_data = DB::table('activity')->where($where)->limit(10)->get();

        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }

        $data = [
            'activity_data'=>$activity_data,
        ];

        return response()->json(['code'=>200,'data'=>$data]);
    }
    //活动详情
    public function getActivityDetail(Request $request)
    {
        try{
            $dateType = $request->input('dateType',4);
            switch ($dateType)
            {
                case 1:
                    $where = [
                        ['is_del','=',1],
                        ['created_at','>=',Carbon::today()],
                        ['created_at','<=',Carbon::now()],
                        ['is_del','=',1],
                    ];
                    break;
                case 2:
                    $where = [
                        ['is_del','=',1],
                        ['created_at','>=',Carbon::yesterday()],
                        ['created_at','<=',Carbon::today()],
                        ['is_del','=',1],
                    ];
                    break;
                case 3:
                    $where = [
                        ['is_del','=',1],
                        ['created_at','>=',Carbon::today()->subDays(7)],
                        ['created_at','<=',Carbon::today()],
                        ['is_del','=',1],
                    ];
                    break;

                case 4:
                    $where = [
                        ['is_del','=',1],
                        ['created_at','>=',Carbon::today()->subDays(30)],
                        ['created_at','<=',Carbon::today()],
                        ['is_del','=',1],
                    ];
                    break;
                default:
                    $where = [
                        ['is_del','=',1],
                        ['created_at','>=',Carbon::today()],
                        ['created_at','<=',Carbon::now()],
                        ['is_del','=',1],
                    ];
            }
            $where[] = ['org_code',ORGCODE];
            $activity_detail = DB::table('activity')->select(DB::raw('SUM(sign_up_num) as sign_up , 
            SUM(student_num + parent_num) as join_num, 
            SUM(PV) as total_PV, SUM(UV) as total_UV ,
            SUM(IP) as total_IP'))
                ->where($where)->first();
            $join_num  = $activity_detail->join_num;
            $sign_up = $activity_detail->sign_up;
            $join_rate = 0;
            if($sign_up<$join_num)
                $join_rate = 1;
            if($sign_up>$join_num)
                $join_rate = round($join_num / $sign_up,2);
            if($sign_up==0 || $join_num==0)
                $join_rate = 0;
            $data = [
                'activity_detail'=>$activity_detail,
                'join_rate'=>$join_rate,
            ];
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>$data]);
    }

}
