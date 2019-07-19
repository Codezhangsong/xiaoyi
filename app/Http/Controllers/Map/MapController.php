<?php

namespace App\Http\Controllers\Map;

use App\Services\Utils\BaiduMap;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MapController extends Controller
{
    public function search()
    {
        $res = BaiduMap::search($query='东方路',$city='上海');

        dd($res);
    }
}
