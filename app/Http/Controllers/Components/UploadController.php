<?php

namespace App\Http\Controllers\Components;

use App\Http\Controllers\CourseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    const EXT_ARR = [
        'pic' => ['gif', 'jpg', 'jpeg', 'png', 'bmp'],
        'media' => ['swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm','mp4'],
        'file' => ['doc', 'pem','docx', 'xls', 'xlsx', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2','csv'],
        'doc'  => ['xls', 'xlsx'],
    ];

    //上传excel类
    public static function  uploadExcel(Request $request)
    {
        try{
            if (!$request->isMethod('POST')) { //判断是否是POST上传
                throw new \Exception('请用post方式提交');
            }
            $fileCharater = $request->file('excel');
            if($fileCharater->isValid()) {
                //获取文件的扩展名
                $ext = $fileCharater->getClientOriginalExtension();

                if(!in_array($ext , self::EXT_ARR['doc']))
                    throw new \Exception('请上传 xls或xlsx的文件类型');
                //获取文件的绝对路径
                $path = $fileCharater->getRealPath();
                //定义文件名
                $filename = Carbon::today()->format('Y-m-d').'-'.$fileCharater->getClientOriginalName();
                //存储文件。disk里面的public
                $res = Storage::disk('public')->put($filename, file_get_contents($path));
                if(!$res)
                    throw new \Exception('上传excel失败');
            }

        }catch(\Exception $e){
            return ['code'=>500,'msg'=>$e->getMessage()];
        }
        return ['code'=>200,'fileName'=>$filename];
    }
        //上传图片类
    public static function uploadImg(Request $request)
    {
        try{
            if (!$request->isMethod('POST')) { //判断是否是POST上传
                throw new \Exception('请用post方式提交');
            }

            $fileCharater = $request->file('cover');

            if ($fileCharater->isValid()) {
                //获取文件的扩展名
                $ext = $fileCharater->getClientOriginalExtension();
                //获取文件的绝对路径
                $path = $fileCharater->getRealPath();
                //定义文件名
                $imgName = time().'.'.$ext;

                if(!in_array($ext , self::EXT_ARR['pic']))
                    throw new \Exception('请上传 gif,jpg, jpeg, png, bmp 类型的图片');
                //存储文件。disk里面的public
                $res = Storage::disk('img')->put($imgName, file_get_contents($path));
                if(!$res)
                    throw new \Exception('上传图片失败');
            }

        }catch(\Exception $e){
            return ['code'=>500,'msg'=>$e->getMessage()];
        }
        return ['code'=>200,'fileName'=>$imgName];
    }

    public static function uploadTeacherImg(Request $request)
    {
        try{
            if (!$request->isMethod('POST')) { //判断是否是POST上传
                throw new \Exception('请用post方式提交');
            }

            $fileCharater = $request->file('teacher_img');

            if ($fileCharater->isValid()) {
                //获取文件的扩展名
                $ext = $fileCharater->getClientOriginalExtension();
                //获取文件的绝对路径
                $path = $fileCharater->getRealPath();
                //定义文件名
                
              //  $imgName = time().'.'.$ext;
              $imgName = 'teacher_'.time().'.'.$ext;

                if(!in_array($ext , self::EXT_ARR['pic']))
                    throw new \Exception('请上传 gif,jpg, jpeg, png, bmp 类型的图片');
                //存储文件。disk里面的public
                $res = Storage::disk('img')->put($imgName, file_get_contents($path));
                if(!$res)
                    throw new \Exception('上传图片失败');
            }

        }catch(\Exception $e){
            return ['code'=>500,'msg'=>$e->getMessage()];
        }
        return ['code'=>200,'fileName'=>$imgName];
    }
}
