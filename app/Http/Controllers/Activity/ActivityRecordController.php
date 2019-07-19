<?php

namespace App\Http\Controllers\Activity;

use App\Model\Activity;
use App\Model\ActivityData;
use App\Model\ActivityLog;
use App\Model\ActivityRecord;
use App\Model\Level;
use App\Model\Parents;
use App\Model\Students;
use App\Model\Tags;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActivityRecordController extends Controller
{

    public function add(Request $request)
    {
        try{

            DB::beginTransaction();
            
            $data = $request->all();

            $json = json_encode($data);

            if(empty($data))
                throw new \Exception('参数为空');

            if(!isset($data['act_id']))
            {
                throw new \Exception('act_id为空');
            }


            $act_id = $data['act_id'];

            unset($data['act_id']);

            $activity = Activity::where('id',$act_id)->first();

            if(!$activity){
                throw new \Exception('未找到对应活动');
            }
            $org_code = $activity->org_code;
            $rule = json_decode($data['rule'],true);
           

//            $rule = json_decode($activity->rule,true);
           
            $time = Carbon::now();

            $student_data = [];
            $parent_data = [];

            foreach ($data as $key => $value)
            {
                if(isset($rule[$key])){
                    $arr[] = [
                        'act_id'=>$act_id,
                        'field_name'=>$rule[$key],
                        'field_value'=>$value,
                        'created_at'=>$time,
                        'updated_at'=>$time,
                    ];
                }
                if(isset($rule[$key]))
                {
                  
                    if($rule[$key] == 'student_name')
                    {
                        $student_data['name'] = $value;
                        $student_data['reg_date'] = Carbon::today();
                        $student_data['created_at'] = Carbon::now();
                        $student_data['updated_at'] = Carbon::now();
                        $student_data['org_code'] = $org_code;
                        $student_data['origin'] = $act_id;
                    }

                    if($rule[$key] == 'student_gender')
                    {
                        $student_data['gender'] = $value;
                    }

                    if($rule[$key] == 'student_school')
                    {
                        $student_data['school'] = $value;
                    }

                    if($rule[$key] == 'student_age')
                    {
                        $student_data['age'] = $value;
                    }

                    if($rule[$key] == 'parent_name')
                    {
                        $parent_data['name'] = $value;
                        $parent_data['reg_date'] = Carbon::today();
                        $parent_data['created_at'] = Carbon::now();
                        $parent_data['updated_at'] = Carbon::now();
                        $parent_data['org_code'] = $org_code;
                        $parent_data['origin'] = $act_id;
                    }

                    if($rule[$key] == 'parent_mobile')
                    {
                        $parent_data['mobile'] = $value;
                    }

                    if($rule[$key] == 'tag_id')
                    {
                        $parent_data['tag_id'] = $value;

                        $tag = Tags::where('id',$value)->first();

                        if($tag)
                            $parent_data['tag_name'] = $tag->tag_name;
                    }

                    if($rule[$key] == 'level_id')
                    {
                        $parent_data['level_id'] = $value;

                        $level = Level::where('id',$value)->first();

                        if($level)
                            $parent_data['level_name'] = $level->level_name;
                    }
                }
            }

            if(!empty($student_data)){
                Students::insert($student_data);
                $activity->student_num = $activity->student_num + 1;
            }

            if(!empty($parent_data)){
                Parents::insert($parent_data);
                $activity->parent_num = $activity->parent_num + 1;
            }
            $activity->save();
            ActivityLog::insert($arr);
            $data = [
                'act_id'=>$act_id,
                'rule'=>$json,
                'created_at'=>$time,
                'updated_at'=>$time,
            ];

            $res = ActivityData::insert($data);

            if(!$res)
                throw new \Exception('插入失败');

            DB::commit();

        }catch (\Exception $e){
            DB::rollback();
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }
       return response()->json(['code'=>200]);
    }

    public function edit(Request $request)
    {
        $id  = $request->input('id');
        $channel_id  = $request->input('channelId');
        $activity_id  = $request->input('activityId');
        $name  = $request->input('name');
        $mobile  = $request->input('mobile');
        $gender  = $request->input('gender',3);
        $comment  = $request->input('comment');
        $datetime = Carbon::now();

        $validator = Validator::make($request->all(), [
            'id'=>'required|integer',
            'activityId'=>'required|integer',
            'channelId'=>'required|integer',
            'name' => 'required|string',
            'mobile' => 'required|digits:11',
            'gender' => 'required|digits:1',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());

            $arr = [
                'id'=>$id,
                'activity_id'=>$activity_id,
                'channel_id'=>$channel_id,
                'name'=>$name,
                'mobile'=>$mobile,
                'comment'=>$comment,
                'gender'=>$gender,
                'created_at'=>$datetime,
                'updated_at'=>$datetime,
            ];

            $res = ActivityRecord::edit($arr);
            if(!$res)
                throw new \Exception('insert fail');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }

    public function search(Request $request)
    {
        $id  = $request->input('id');
        $page  = $request->input('page',1);
        $page = $page -1;
        $limit  = $request->input('limit',10);
        $offset = $limit * $page;

        $validator = Validator::make($request->all(),[
            'id' => 'numeric',
            'page' => 'numeric',
            'limit' => 'numeric',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());

            $where[] = ['is_del','=',1];
            if(ORGCODE)
                $where[] = ['org_code','=',ORGCODE];
            if($id)
                $where[] = [ 'id','=',$id];

            $arr = [
                'where'=>$where,
                'limit'=>$limit,
                'offset'=>$offset,
            ];
            $result = ActivityRecord::search($arr);
            if($result['count']==0)
                throw new \Exception('暂无数据');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>['data'=>$result['data'],'count'=>$result['count']]]);
    }

    public function del(Request $request)
    {
        $ids  = $request->input('ids');

        $validator = Validator::make($request->all(), [
            'ids'=>'required',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $id_arr = json_decode($ids,true);
            $res = ActivityRecord::del($id_arr);
            if(!$res)
                throw new \Exception('删除失败');
        }catch(\Exception $e){
            return response()->json(['code'=>500,'error'=>$e->getMessage()]);
        }
        return response()->json(['code'=>200]);
    }

}
