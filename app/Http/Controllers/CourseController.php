<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Components\UploadController;
use App\Model\Course;
use App\Services\Utils\Helps;
use App\Services\Utils\Http;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Excel;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public $id;
    public $status = [1,2,3,4,5,6];
    const CLASS_TYPE_ARR = [
        '素质教育','学科教育','国际教育'
    ];
    public function uploadCourse(Request $request)
    {
        $class_type = $request->input('classType','素质教育');
        $class_tag = $request->input('classTag');


        $validator = Validator::make($request->all(),[
            'classType'=>'required|string',
            'classTag'=>'required|string',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            if(!in_array($class_type, self::CLASS_TYPE_ARR))
                    throw new \Exception('classType 必须是 素质教育，学科教育，国际教育的一种');
            $res = UploadController::uploadExcel($request);//上传excel

            if(!isset($res['fileName']))
                throw new \Exception($res['msg']);

            $res = $this->readExcel($res['fileName']); //读取excel 获得课程id
            if($res['code']!= 200 || !isset($res['id']))
                throw new \Exception($res['msg']);
            $id = $res['id'];

            $res = UploadController::uploadImg($request); //上传图片
            $teacher_res = UploadController::uploadTeacherImg($request);//上传老师图
            if(!isset($res['fileName']) || !isset($teacher_res['fileName']))
                throw new \Exception($res['msg']);

            $img_path = public_path('uploads/img').'/'.$res['fileName'];
            $teacher_img_path = public_path('uploads/img').'/'.$teacher_res['fileName'];
            if(!file_exists($img_path) || !file_exists($teacher_img_path))
                throw new \Exception('上传图片不存在 请检查上传文件位置');

            $img_read_path = url('/').'/img?image='.$res['fileName'];
           // $teacher_img_read_path = url('/').'/img?image='.!['fileName'];
            $teacher_img_read_path = url('/').'/img?image='.$teacher_res['fileName'];
            $res = Course::where('id',$id)->update([
                'cover_img'=>$img_read_path,
                // 'course_introduce_img'=>$teacher_img_read_path,
                // 'teacher_img'=>$img_read_path,
                'course_introduce_img'=>$img_read_path,
                'teacher_img'=>$teacher_img_read_path,

                'class_type'=>$class_type,
                'class_tag'=>$class_tag,
                'updated_at'=>Carbon::now(),
            ]);

            if(!$res)
                throw new \Exception('更新失败');
        }catch (\Exception $e){
            return ['code'=>500,'msg'=>$e->getMessage()];
        }

        return ['code'=>200,'id'=>$id];
    }

    public function readExcel($filename)
    {
        $filePath = public_path('uploads').'/'.$filename;
        if(!file_exists($filePath))
            return response()->json(['code'=>500,'msg'=>'文件路径不存在']);
        try{
            Excel::load($filePath, function($reader) use( &$res ) {
                
                $reader = $reader->getSheet(0);
              
                

                $res = $reader->toArray();
                
               

                if(empty($res))
                    throw new \Exception('表格无内容');

                if(!isset($res[1][1]) || empty($res[1][1]))
                    throw new \Exception('课程名称为空');
                if(!isset($res[3][5]) || empty($res[3][5]))
                    throw new \Exception('教师名称为空');
                if(!isset($res[3][1]) || empty($res[3][1]))
                    throw new \Exception('账户手机为空');

                if(!isset($res[2][1]) || empty($res[2][1]))
                    throw new \Exception('师资机构介绍为空');

                if(!isset($res[1][4]) || empty($res[1][4]))
                    throw new \Exception('课程价格为空');

                if(!isset($res[1][6]) || empty($res[1][6]))
                    throw new \Exception('课时数不能为空');

                if(!isset($res[4][2]) || empty($res[4][2]))
                    throw new \Exception('课程亮点不能为空');

                $courseName = $res[1][1];
                $teachName = $res[3][5];
                $account = (int)$res[3][1]; //账户手机
                $result = Helps::check_mobile($account);
                if($result['code']!=200)
                    throw new \Exception($result['msg']);
                $teacherInfo = $res[2][1];
                $textbook = $res[8][2];
                $price = $res[1][4];
                $class_hour = $res[1][6];
                $desc = $res[7][2]; //课程简介
                $class = $res[6][2]; //适用年级
                $difficult = $res[5][2];
                $feature = $res[4][2].'|'.$res[4][4].'|'.$res[4][6];
                $courseObject = $res[7][2];
                $course_content = $res[9][1];
                array_pop($res);

                foreach ($res as $k =>$v){
                    if($k<13){
                        continue;
                    }
                    if(!is_numeric($v[0])){
                        break;
                    }

                    $lesson_data[] = $v;
                }



                $res = array_chunk($res,13);
                if(!isset($res[1][0][3]) || empty($res[1][0][3]))
                    throw new \Exception('课程开始日期为空');
                $start_date = $res[1][0][3].' '.$res[1][0][4]; //课程开始日期
                $end_date = end($res[1])[3].' '.end($res[1])[3];//课程结束日期
                DB::beginTransaction();
                $teacher_res = Course::where([
                    ['account','=',$account],
                    ['teacher_id','!=',null],
                ])->first();

                if($teacher_res){
                    $teacher_id = $teacher_res->teacher_id;
                }else{
                    $teacher_id = null;
                }

                $arr = [
                    'course_name'=>$courseName,
                    'teacher_name'=>$teachName,
                    'teacher_id'=>$teacher_id,
                    'account'=>$account,
                    'desc'=>$desc,
                    'code'=>md5(time()),
                    'course_content'=>$course_content,
                    'course_obj'=>$courseObject,
                    'course_feature'=>$feature,
                    'class'=>$class,
                    'class_hour'=>$class_hour,//课时数
                 //   'course_num'=>count($res[1]),//课节数
                 'course_num'=>count($lesson_data),//课节数
                    'price'=>$price,
                    'teacher_info'=>$teacherInfo,
                    'reg_date'=>Carbon::now()->format('Y-m-d'),
                    'start_date'=>$start_date,
                    'end_date'=>$end_date,
                    'org_code'=>ORGCODE,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                    'textbook'=>$textbook,
                    'class_difficult'=>$difficult
                ];

                $id = DB::table('course')->insertGetId($arr);

                if(!$id)
                    throw new \Exception('插入失败');
                $this->id = $id;

            //    foreach ($res[1] as $key =>$value)
            foreach ($lesson_data as $key =>$value)
                {
                    $lesson_date = str_replace(".","-",$value[3]);

                    if(date('Y-m-d',strtotime($lesson_date)) != $lesson_date){
                        throw new \Exception('课节开始日期格式有误，正确例子： 2019.01.01');
                    }

                    $lessonArr[] = [
                        'course_id'=>$id,
                        'code'=>md5($value[1]),
                        'lesson_num'=>$value[0],
                        'lesson_name'=>$value[1],
                        //'lesson_date'=>$value[3],
                        'lesson_date'=>$lesson_date,
                        'start_time'=>$value[4],
                        'end_time'=>$value[6],
                    ];
                }
                DB::table('course_detail')->insert($lessonArr);
                DB::commit();
            });

        }catch (\Exception $e){
            DB::rollBack();
            return ['code'=>500,'msg'=>$e->getMessage()];
        }
        return ['code'=>200,'id'=>$this->id];
    }

    public function search(Request $request)
    {
        $name = $request->input('courseName');
        $id = $request->input('id');
        $page = $request->input('page',1);
        $status = $request->input('status');
        $limit = $request->input('limit',10);
        $order = $request->input('order','desc');
        $page = $page - 1;
        $offset = $page * $limit;
        $where[] = ['is_del','=',1];
        if($id)
            $where[] = ['id','=',$id];

        if($name)
            $where[] = ['course_name','like','%'.$name.'%'];
        if($status)
            $where[] = ['status','=',$status];

         
        if(ORGCODE)
            $where[] = ['org_code','=',ORGCODE];

        $arr = [
            'page'=>$page,
            'limit'=>$limit,
            'offset'=>$offset,
            'where'=>$where,
            'order'=>$order,
        ];
       
        try{
            $validator = Validator::make($request->all(),[
                'courseName'=>'string',
                'page'=>'integer',
                'limit'=>'integer',
                'status'=>'integer',
            ]);

            if($validator->fails())
                throw new \Exception($validator->errors());

            $result = Course::search($arr);

          

            if($result['count']==0){
                throw new \Exception('暂无数据');
            }

        }catch (\Exception $e){
            return response()->json(['code'=>500,'err'=>$e->getMessage()]);
        }


        return response()->json(['code'=>200,'data'=>['data'=>$result['data'],'count'=>$result['count']]]);
    }

    public function get(Request $request)
    {
        $id = $request->input('id');
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $page = $page - 1;
        $offset = $page * $limit;
        try{
            $validator = Validator::make($request->all(),[
                'id'=>'integer',
                'page'=>'integer',
                'limit'=>'integer',
            ]);
            if($validator->fails())
                throw new \Exception($validator->errors());

            $res = DB::table('course')->where('id',$id)->first();
            if(!$res)
                throw new \Exception('id 不存在');
            $count = DB::table('course_detail')->where('course_id',$res->id)->get()->count();
            $detail = DB::table('course_detail')->where('course_id',$res->id)->offset($offset)->limit($limit)->get()->toArray();
            if(empty($detail))
                throw new \Exception('暂无课程详情');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'err'=>$e->getMessage()]);
        }

        
        return response()->json(['code'=>200,'data'=>['course_info'=>$res,'count'=>$count,'lesson'=>$detail]]);
    }

    public function download(Request $request,$type)
    {
       
        
        
     

        if($type=="1"){
            $file_dir =    realpath(base_path('public/download')).'/2019.xlsx';
        }elseif($type=="2"){
            $file_dir =    realpath(base_path('public/download')).'/2019_1.xlsx';
        }

        
      //  $file_dir =    realpath(base_path('public/download')).'/2019.xlsx';

      

        if (! file_exists ( $file_dir )) {
            header('HTTP/1.1 404 NOT FOUND');
        } else {
            //以只读和二进制模式打开文件
            $file = fopen ( $file_dir, "rb" );

            //告诉浏览器这是一个文件流格式的文件
            Header ( "Content-type: application/octet-stream" );
            //请求范围的度量单位
            Header ( "Accept-Ranges: bytes" );
            //Content-Length是指定包含于请求或响应中数据的字节长度
            Header ( "Accept-Length: " . filesize ( $file_dir ) );
            //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
            Header ( "Content-Disposition: attachment; filename=2019.xlsx" );

            //读取文件内容并直接输出到浏览器
            echo fread ( $file, filesize ($file_dir) );
            fclose ( $file );
            exit ();
        }
    }

    public function audit(Request $request)
    {

      
        
        $status = $request->input('status');
        $id = $request->input('id');

        $validator = Validator::make($request->all(),[
            'status'=>'required|integer',
            'id'=>'required|integer',
        ]);


        try{
            if($validator->fails())
                throw new \Exception($validator->errors());

            if(!isset($this->status[$status]))
                throw new \Exception('审核状态值超出范围');

            $course_obj = Course::where('id',$id)->first();

            if(!$course_obj)
                throw new \Exception('未查询到课程id');

            $course_obj->status=$status;
            $course_obj->updated_at = Carbon::now();
            $res = $course_obj->save();

            if(!$res)
                throw new \Exception('修改失败');

        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }
            return response()->json(['code'=>200,'msg'=>'success']);

    }


}

