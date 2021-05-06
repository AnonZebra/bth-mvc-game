<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class Fallback404Controller extends Controller
{
    public function fallback() {
        return View::make('fallback-404');
    }
}
