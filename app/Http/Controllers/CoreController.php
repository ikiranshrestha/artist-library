<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoreController extends Controller
{
    public function loginPage()
    {
        return view('core.auth.login');
    }

    public function registerPage()
    {
        return view('core.auth.register');
    }

    public function dashboard()
    {
        return view('core.dashboard');
    }

    public function userTab(Request $request)
    {
        return view('core.user.user-tab');
    }
}
