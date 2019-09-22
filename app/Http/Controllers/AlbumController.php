<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlbumController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
	
    public function index()
	{
		echo "Welcome to album controller";
		die;
	}
}
