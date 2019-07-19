<?php

namespace App\Http\Controllers;

use App\Model\Orgs;
use App\Model\Parents;
use App\Model\Students;
use App\Services\Utils\BaiduTj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\Exam;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $module = $request->input('module');

        switch ($module)
        {
            case 'exam':
                $id  = $request->input('id');
                $schoolId  = $request->input('school_id');
                $career  = $request->input('career');
                $classId  = $request->input('class_id');
                $page  = $request->input('page',1);
                $page = $page -1;
                $limit  = $request->input('limit',999999);
                $offset = $limit * $page;
            $validator = Validator::make($request->all(),[
                'module' => 'required|string',
                'id' => 'numeric',
                'schoolId' => 'numeric',
                'classId' => 'numeric',
                'career' => 'string',
                'page' => 'numeric',
                'limit' => 'numeric',
            ]);

            try{
                if($validator->fails())
                    throw new \Exception($validator->errors());
                    $where=array();

                $where[] = ['is_del','=',1];
                if($id)
                    $where[] = [ 'id','=',$id];
                if($schoolId)
                    $where[] = ['school_id','=',$schoolId];
                if($classId)
                    $where[] = ['class_id','=',$classId];
                if($career){
                    $where[] = ['career','=',$career];
                }

                $arr = [
                    'where'=>$where,
                    'limit'=>$limit,
                    'offset'=>$offset,
                ];
                $result = Exam::search($arr);

                $column = ['编号','姓名','电话','学校名称','事业部','年级','报名时间'];

                $data[] = $column;

                foreach ($result['data'] as $key=>$value)
                {
                    $data[] = [$value->id,$value->name,$value->mobile,$value->school,$value->career,$value->class,$value->reg_date];
                }

            }catch (\Exception $e){
                return response()->json(['code'=>500,'error'=>$e->getMessage()]);
            }
            break;
            case 'student':

                $id  = $request->input('id');
                $intention  = $request->input('intention');
                $startDate = $request->input('startDate');
                $endDate = $request->input('endDate');
                $validator = Validator::make($request->all(),[
                    'module' => 'required|string',
                    'id' => 'numeric',
                    'intention' => 'string',
                    'startDate' => 'date',
                    'endDate' => 'date',
                ]);

                try{
                    if($validator->fails())
                        throw new \Exception($validator->errors());

                    $where[] = ['is_del','=',1];

                    if($id)
                        $where[] = [ 'id','=',$id];
                    if($intention)
                        $where[] = ['intention','=',$intention];
                    if($startDate && $endDate){
                        $where[] = ['reg_date','>=',$startDate];
                        $where[] = ['reg_date','<=',$endDate];
                    }
                    if(ORGCODE)
                        $where[] = [ 'org_code','=',ORGCODE];
                    $arr = [
                        'where'=>$where,
                    ];

                    $result = Students::export($arr);

                    if($result['count']==0)
                        throw new \Exception('暂无数据');

                    $column = ['编号','学生姓名','家长电话','意向课程','年龄','注册日期'];

                    $data[] = $column;

                    foreach ($result['data'] as $key=>$value)
                    {
                        $data[] = [$value->id,$value->name,$value->parent_name,$value->parent_mobile,$value->intention,$value->age,$value->reg_date];
                    }

                }catch (\Exception $e){
                    return response()->json(['code'=>500,'error'=>$e->getMessage()]);
                }
                break;
            case 'parent':
                $id = $request->input('id');
                $levelId = $request->input('levelId');
                $startDate = $request->input('startDate');
                $endDate = $request->input('endDate');

                $validator = Validator::make($request->all(),[
                    'id' => 'numeric',
                    'levelId' => 'numeric',
                    'startDate' => 'date',
                    'endDate' => 'date',
                    'page' => 'numeric',
                    'limit' => 'numeric',
                ]);


                try{
                    if($validator->fails())
                        throw new \Exception($validator->errors());

                    $where[] = ['is_del','=',1];
                    $where[] = ['use_flag','=',1];
                    if(ORGCODE)
                        $where[] = ['org_code','=',ORGCODE];

                    if($id)
                        $where[] = ['id','=',$id];

                    if($levelId)
                        $where[] = ['level_id','=',$levelId];

                    if($startDate && $endDate){
                        $where[] = ['reg_date','>=',$startDate];
                        $where[] = ['reg_date','<=',$endDate];
                    }
                    $arr = [
                        'where'=>$where,
                    ];
                    $result = Parents::export($arr);
                    if($result['count']==0)
                        throw new \Exception('暂无数据');

                    $column = ['编号','姓名','手机号码','家长账号','家长等级','注册时间'];

                    $data[] = $column;

                    foreach ($result['data'] as $key=>$value)
                    {
                        $data[] = [$value->id,$value->name,$value->name,$value->mobile,$value->level,$value->tag_name,$value->reg_date];
                    }
                }catch (\Exception $e){
                    return response()->json(['code'=>500,'error'=>$e->getMessage()]);
                }
                break;
            case 'org':
                $orgName = $request->input('orgName');
                $useFlag = $request->input('useFlag');
                $startDate = $request->input('startDate');
                $endDate = $request->input('endDate');
                $where[] = ['is_del','=',1];

                try{
                    $validator = Validator::make($request->all(),[
                        'id' =>'integer',
                        'orgName' =>'string',
                        'useFlag' =>'integer|digits:1',
                    ]);

                    if($orgName)
                        $where[] = ['org_name','=',$orgName];
                    if($useFlag)
                        $where[] = ['use_flag','=',$useFlag];

                    if($startDate != null && $endDate != null){
                        $where[] = ['created_at','>=' ,$startDate];
                        $where[] = ['created_at','<=' ,$endDate];
                    }

                    $arr = [
                        'where'=>$where,
                    ];

                    if($validator->fails())
                        throw new \Exception($validator->errors());

                    $result = Orgs::export($arr);

                    if($result['count']==0)
                        throw new \Exception('暂无数据');

                    $column = ['编号','机构名称','校区','添加时间'];

                    $data[] = $column;

                    foreach ($result['data'] as $key=>$value)
                    {
                        $data[] = [$value->id,$value->org_name,$value->distinction,$value->created_at];
                    }

                }catch (\Exception $e){
                    return response()->json(['code'=>500,'error'=>$e->getMessage()]);
                }

                break;

            default:
                return response()->json(['code'=>500,'error'=>'module不存在']);
                break;
        }

        Excel::create($module,function($excel) use ($data){
            $excel->sheet('worksheet', function($sheet) use ($data){
                $sheet->rows($data);
            });
        })->export();
    }
}
