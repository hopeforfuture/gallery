@extends('layouts.user-home')

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
                Edit a  Photo <a href="{{ route('album.view.list', base64_encode($photo->album->id)) }}" class="label label-primary pull-right">Back</a>
				<br/><span>Album Name:</span><span style="color:red;font-weight:bold;">{{ $photo->album->album_name }}</span>
            </div>
            <div class="panel-body">
                <form action="{{ route('album.photo.update', base64_encode($photo->id)) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-sm-4" >Photo Title</label>
                        <div class="col-sm-6">
                            <input required type="text" name="title" id="title" class="form-control" value="{{ $photo->title }}">
                        </div>
                    </div>
					
                    
					<div class="form-group">
                        <label class="control-label col-sm-4" >Photo View Type</label>
                        <div class="col-sm-6">
                            <select required class="form-control" name="photo_status" id="photo_status">
								<option value="">---Select Category---</option>
								<option @if($photo->photo_status == 1) selected @endif value="1">Everybody can view</option>
								<option @if($photo->photo_status == 0) selected @endif value="0">Only owner can view</option>
							</select>
                        </div>
                    </div>
					
					<div class="form-group">
                        <label class="control-label col-sm-4" >Photo</label>
                        <div class="col-sm-6">
							<img id="output" src='{{ asset("uploads/photos/thumb/$photo->photo_name") }}' />
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