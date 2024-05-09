<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function checkLogout(Request $request)
    {
        dump($request->user());
        
        if (Auth::check()) {
            // Người dùng đã đăng nhập, thực hiện mã lệnh tại đây
            dump("có đăng nhập");
        }else{
            dump("chưa đăng nhập2");
        }
        die;
    }
}
