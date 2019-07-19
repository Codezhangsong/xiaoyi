<?php

namespace App\Http\Controllers\Image;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    public function get(Request $request)
    {
        $imageName = $request->input('image');
        $image_path = public_path('uploads/img').'/'.$imageName;
        if(!file_exists($image_path)){
            //报404错误
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
            exit;
        }
        header('Content-type: image/jpg');
        echo file_get_contents($image_path);
    }
}
