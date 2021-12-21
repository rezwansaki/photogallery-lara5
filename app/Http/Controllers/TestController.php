<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return $settings;
    }
}
