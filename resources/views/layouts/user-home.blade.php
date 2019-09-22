<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Laravel CRUD Operations - Basic</title>

        <!-- bootstrap minified css -->
        <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
		<link href="{{ captcha_layout_stylesheet_url() }}" type="text/css" rel="stylesheet">
        <!-- jQuery library -->
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
		<script type="text/javascript" src="{{ URL::asset('js/jquery.js') }}"></script>
        <!-- bootstrap minified js -->
        <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- custom CSS -->
        <style>
        h1{font-size:23px;}
        .pull-left h2{margin-top:0;font-size:20px;}
        </style>
    </head>

    <body>
		<div class="header">
			<a href="{{ route('user.logout')}}" class="label label-primary pull-right">Logout</a>
			<a href="{{ route('user.reset.password')}}" class="label label-primary pull-right">Change Password</a>
		</div>
		<div class="sidebar">
			<li><a href="{{ route('album.index') }}">Manage Album</a></li>
		</div>
        <div class="container-fluid">
            @yield('content')
        </div>
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog">
			
			  <!-- Modal content-->
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title" style="color:red;font-weight:bold;">!Oops</h4>
				</div>
				<div class="modal-body">
				  
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			  </div>
			  
			</div>
		</div>
    </body>
</html>