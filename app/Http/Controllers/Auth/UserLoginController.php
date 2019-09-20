<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use Session;
use App\User;

class UserLoginController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest')->except('logout');
	  $this->middleware('auth')->except('showLoginForm', 'dologin', 'signup', 'signupprocess');
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
		//echo 'Signup Page<br/>';
		//echo Hash::make('test123');
		//die;
		return view('auth.user-signup');
	}
	
	public function signupprocess(Request $request)
	{
		$this->validate($request, [
			'name'=>'required',
			'email'=>'required|email|unique:users',
			'password'=>'required|confirmed|min:6',
			'CaptchaCode'=>'required|valid_captcha'
		]);
		
		$postdata = $request->only('name', 'email', 'password');
		$postdata['password'] = Hash::make($postdata['password']);
		$user = new User($postdata);
		$user->save();
		
		$credentials = $request->only('email', 'password');
		
		if(Auth()->attempt($credentials))
		{
			Session::flash('success_msg', 'Signup process is successful.');
			return redirect()->route('user.dashboard');
		}
		
	}
	
	public function logout()
	{
		Auth()->logout();
		
		return redirect()->route('user.login');
	}
}
