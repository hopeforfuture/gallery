<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function dashboard()
	{
		return view('user.dashboard');
	}
}
