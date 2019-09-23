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
        return view('login');
    }

    public function signIn(Request $request) {
        dd($request);

        if ($request->get('username') != 'admin') {
            return back()->withErrors();
        }
        if ($request->get('password') != '123456') {
            return back()->withErrors();
        }

        session('userInfo', $request->get('username'));
        return redirect('/');
    }
}
