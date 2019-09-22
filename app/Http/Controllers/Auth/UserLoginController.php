<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Hash;
use Session;


class UserLoginController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest')->except('logout', 'resetpassword', 'resetpasswordprocess');
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
	
	public function resetpassword()
	{
		return view('auth.user-reset-password');
	}
	
	public function resetpasswordprocess(Request $request)
	{
		$this->validate($request, [
			'old_password'=>'required|min:6',
			'password'=>'required|confirmed|min:6',
			'CaptchaCode'=>'required|valid_captcha'
		]);
		
		$user = auth()->user();
		$postdata = $request->only('password', 'old_password');
		$old_password = $postdata['old_password'];
		$new_password = $postdata['password'];
		$userinfo = User::find($user->id);
		
		if(!(Hash::check($old_password, $userinfo->password)))
		{
			return redirect()->back()->with('status', 'current password does not match.');
		}
		elseif(strcmp($old_password, $new_password) == 0)
		{
			return redirect()->back()->with('status', 'New password could not be same as old password.');
		}
		
		$updatedinfo = $request->only('password');
		$updatedinfo['password'] = Hash::make($updatedinfo['password']);
		
		User::find($user->id)->update($updatedinfo);
		Session::flash('success_msg', 'Password updated successfully.');
		return redirect()->route('user.dashboard');
	}
	
	public function logout()
	{
		Auth()->logout();
		
		return redirect()->route('user.login');
	}
}
