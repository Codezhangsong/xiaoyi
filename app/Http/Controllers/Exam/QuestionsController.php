<?php

namespace App\Http\Controllers\Exam;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Model\Exam\Questions;
use Carbon\Carbon;

class QuestionsController extends Controller
{
    public function search(Request $request)
    {
        $id  = $request->input('id');
        $quesName  = $request->input('quesName');
        $answerName  = $request->input('answerName');
        $classtypeId  = $request->input('classtypeId');
        $levelId  = $request->input('levelId');
        $ageRageId  = $request->input('ageRageId');
        $quesTypeId  = $request->input('quesTypeId');
        $page  = $request->input('page',1);
        $page = $page -1;
        $limit  = $request->input('limit',10);
        $offset = $limit * $page;

        $validator = Validator::make($request->all(),[
            'id' => 'numeric',
            'school' => 'string',
            'career' => 'string',
            'class' => 'string',
            'name' => 'string',
            'mobile' => 'digits:11',
            'password' => 'string',
            'page' => 'numeric',
            'limit' => 'numeric',
        ]);

        try{
            if($validator->fails())
                throw new \Exception($validator->errors());

            $where[] = ['questions.is_del','=',1];
            if($id){
                $where[] = ['questions.id','=',$id];
            }
            if($id){
                $where[] = ['questions.id','=',$id];
            }

            $arr = [
                'where'=>$where,
                'limit'=>$limit,
                'offset'=>$offset,
            ];
            $result = Questions::search($arr);
            if($result['count']==0)
                throw new \Exception('暂无数据');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'data'=>'','msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>['data'=>$result['data'],'count'=>$result['count']],'msg'=>'']);
    }
    public function add(Request $request)
    {

        $name = $request->input('name');
        $creator = $request->input('creator');

        $validator = Validator::make($request->all(),[
            'name'=>'required|string',
            'creator'=>'required|string',
        ]);
        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $arr = [
                'name'=>$name,
                'creator'=>$creator,
                'updated_at'=>Carbon::now(),
                'created_at'=>Carbon::now(),
            ];
            //判断数据是否已存在
            if(QuesType::single(['name'=>$name])){
                return response()->json(['code'=>500,'msg'=>'试题类型已存在']);
            }
            $res = QuesType::add($arr);
            if(!$res)
                throw new \Exception('插入失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200]);
    }
    //上下架
    public function statusActive(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $status = $status==1?2:1;  //1上架的下架2下架的上架
        $msg = $status==1?'下架':'上架';  //1上架的下架2下架的上架
        $validator = Validator::make($request->all(),[
            'id'=>'required|integer',
            'status'=>'required|string',
        ]);
        try{
            if($validator->fails())
                throw new \Exception($validator->errors());
            $arr = [
                'id'=>$id,
                'status'=>$status,
                'updated_at'=>Carbon::now(),
            ];
            $res = Questions::edit($arr);
            if(!$res)
                throw new \Exception($msg.'失败');
        }catch (\Exception $e){
            return response()->json(['code'=>500,'msg'=>$e->getMessage()]);
        }

        return response()->json(['code'=>200,'data'=>'','msg'=>$msg.'成功']);
    }
    /**
     * 显示文章列表.
     *
     * @return Response
     */
    public function index()
    {
        echo 12333;
    }

    /**
     * 创建新文章表单页面
     *
     * @return Response
     */
    public function create()
    {
        //
    }
    /**
     * 将新创建的文章存储到存储器
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * 显示指定文章
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
        echo 2334454;
    }

    /**
     * 显示编辑指定文章的表单页面
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        echo 12;
    }

    /**
     * 在存储器中更新指定文章
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
//        echo 23444;
        var_dump(222);exit;
    }

    /**
     * 从存储器中移除指定文章
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
