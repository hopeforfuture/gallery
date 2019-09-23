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
                Edit an  Album <a href="{{ route('album.index') }}" class="label label-primary pull-right">Back</a>
            </div>
            <div class="panel-body">
                <form action="{{ route('album.update', base64_encode($album->id)) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-sm-4" >Album Name</label>
                        <div class="col-sm-6">
                            <input required type="text" name="album_name" id="album_name" class="form-control" value="{{ $album->album_name }}">
                        </div>
                    </div>
					
                    <div class="form-group">
                        <label class="control-label col-sm-4" >Album Details</label>
                        <div class="col-sm-6">
                            <textarea rows="10" required name="album_description" id="album_description" class="form-control">{{ $album->album_description }}</textarea>
                        </div>
                    </div>
					
					<div class="form-group">
                        <label class="control-label col-sm-4" >Album Category</label>
                        <div class="col-sm-6">
                            <select required class="form-control" name="category_id" id="category_id">
								<option value="">---Select Category---</option>
								@foreach($categories as $category)
								<option @if($category->id == $album->category_id) selected @endif value="{{ $category->id }}">{{ $category->cat_name }}</option>
								@endforeach
							</select>
                        </div>
                    </div>
					
					<div class="form-group">
                        <label class="control-label col-sm-4" >Cover Image</label>
                        <div class="col-sm-6">
                            <input  type="file" name="album_cover" id="album_cover" class="form-control" onchange="loadFile(event)"><br/>
							<img id="output" src='{{ asset("uploads/albums/thumb/$album->album_cover") }}' />
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

<script>
  var loadFile = function(event) {
    var output = document.getElementById('output');
	output.width = 80;
	output.height = 60;
    output.src = URL.createObjectURL(event.target.files[0]);
  };
</script>