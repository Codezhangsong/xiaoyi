<?php

namespace App\Http\Controllers;

use App\Services\Utils\Des;
use Illuminate\Http\Request;

class SecretController extends Controller
{


    public function encrypt()
    {
        $des = new Des();
        $res = $des->encrypt('张淞');
        $res = $des->decrypt($res);
    }



}
