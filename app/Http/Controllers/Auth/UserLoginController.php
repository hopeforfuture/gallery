<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class UserLoginController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest')->except('logout');
	  $this->middleware('auth')->except('showLoginForm', 'dologin', 'signup');
    }
	
	public function showLoginForm()
    {
      return view('auth.user-login');
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
      if (Auth()->attempt(['email' => $request->email, 'password' => $request->password])) 
	  {
        return redirect()->route('user.dashboard');
      }
      // if unsuccessful, then redirect back to the login with the form data
      return redirect()->back()->with('status', 'Login Failed.')->withInput($request->only('email'));
    }
	
	public function signup()
	{
		echo 'Signup Page';
		die;
	}
	
	public function logout()
	{
		Auth()->logout();
		
		return redirect()->route('user.login');
	}
}
