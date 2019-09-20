@extends('layouts.user-home')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			@if(Session::has('success_msg'))
				<div class="alert alert-success">{{ Session::get('success_msg') }}</div>
			@endif
			<h1 align="center">User Dashboard</h1>
		</div>
	</div>
	
@endsection