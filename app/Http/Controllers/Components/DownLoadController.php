<?php

namespace App\Http\Controllers\Components;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DownLoadController extends Controller
{
    public function download()
    {
        return response()->download(
            realpath(base_path('public/download')).'/2019.xlsx'
        );
    }
}
