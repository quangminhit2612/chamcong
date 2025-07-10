<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PowerPanelController extends Controller
{
    // Yêu cầu người dùng phải đăng nhập
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Hàm hiển thị trang quản lý điện
    public function index()
    {
        return view('power.index');
    }
}
