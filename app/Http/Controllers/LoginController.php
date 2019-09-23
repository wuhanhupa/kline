<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * 登录验证
 * Class LoginController
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
    //
    public function index() {
        //dd(session('userInfo'));
        return view('login');
    }

    public function signIn(Request $request) {

        if ($request->get('username') != 'admin') {
            return back()->withErrors();
            dd('username error');
        }
        if ($request->get('password') != '123456') {
            return back()->withErrors();
            dd('password error');
        }

        session(['userInfo' => 'admin']);
        return redirect('/');
    }
}
