@extends('layouts.user-login')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @if($errors->any())
            <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach()
            </div>
        @endif
        <div class="panel panel-default">
            <div class="panel-heading">
                 <a href="{{ route('user.login') }}" class="label label-primary pull-right">Login</a>
            </div>
            <div class="panel-body">
                <form action="{{ route('user.signup.submit') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-sm-4" >Name</label>
                        <div class="col-sm-6">
                            <input required type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                        </div>
                    </div>
					
                    <div class="form-group">
                        <label class="control-label col-sm-4" >Email Address</label>
                        <div class="col-sm-6">
                           <input required type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"> 
                        </div>
                    </div>
					
					<div class="form-group">
                        <label class="control-label col-sm-4" >Password</label>
                        <div class="col-sm-6">
                            <input required type="password" name="password" id="password" class="form-control" > 
                        </div>
                    </div>
					
					<div class="form-group">
                        <label class="control-label col-sm-4" >Confirm Password</label>
                        <div class="col-sm-6">
                            <input required type="password" name="password_confirmation" id="password_confirmation" class="form-control" > 
                        </div>
                    </div>
					
					<div class="form-group">
                        <label for="CaptchaCode" class="control-label col-sm-4" ></label>
                        <div class="col-sm-6">
                            {!! captcha_image_html('ContactCaptcha') !!}
                                <input id="CaptchaCode" type="text" class="form-control" name="CaptchaCode"  required autofocus>
                        </div>
                    </div>
					
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-6">
                            <input type="submit" class="btn btn-default" value="Save" />
                        </div>
                    </div>
					
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
