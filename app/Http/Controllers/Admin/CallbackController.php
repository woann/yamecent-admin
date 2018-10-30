<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function uploadCallback(Request $request)
    {
        file_put_contents('1.txt',$request->getContent());
    }

    public function transcodeCallback(Request $request)
    {
        file_put_contents('2.txt',$request->getContent());
    }
}