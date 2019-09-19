<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AdminLoginController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest:admin')->except('logout');
	  $this->middleware('auth:admin')->except('showLoginForm', 'dologin');
    }
	
	public function showLoginForm()
    {
      return view('auth.admin-login');
    }
    public function dologin(Request $request)
    {
      // Validate the form data
      $this->validate($request, [
        'email'   => 'required|email',
        'password' => 'required|min:6',
		'CaptchaCode' => 'required|valid_captcha'
      ]);
	  
      // Attempt to log the user in
      if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) 
	  {
        return redirect()->route('admin.dashboard');
      }
      // if unsuccessful, then redirect back to the login with the form data
      return redirect()->back()->with('status', 'Login Failed.')->withInput($request->only('email'));
    }
	
	public function logout()
	{
		Auth::guard('admin')->logout();
		
		return redirect()->route('admin.login');
	}
}
