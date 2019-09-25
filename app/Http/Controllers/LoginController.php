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

    /**
     * 登录处理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function signIn(Request $request) {

        if ($request->get('username') != 'admin') {
            return back()->withErrors(['用户名错误']);
        }
        if ($request->get('password') != '123456') {
            return back()->withErrors(['密码错误']);
        }

        session(['userInfo' => 'admin']);
        return redirect('/');
    }

    /**
     * 退出登录
     */
    public function signOut()
    {
        session()->forget('userInfo');
        return redirect('/');
    }
}
