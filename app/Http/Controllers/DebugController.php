<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DebugController extends Controller
{
    public function debug() {
        return View::make('debug');
    }
}
