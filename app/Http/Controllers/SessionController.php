<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;

class SessionController extends Controller
{
    public function sessionInfo() {
        return View::make('session');
    }

    public function sessionDestroy() {
        Session::flush();
        return redirect('/session');
    }
}
