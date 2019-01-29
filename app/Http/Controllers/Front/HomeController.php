<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('front.main.home');
    }

    public function test()
    {
       $arry=  sub_words('WeSSaM M. Jibreen', 1, 1);
//        $array = explode(' ','WeSSaM Jibreen');
        dd($arry);
        dd(implode(' ',array_splice($array,0)));
        dd(user()->followers,user()->following );
    }

}
