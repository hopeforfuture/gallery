@extends('layouts.admin-home')

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
                Add a New Category <a href="{{ route('category.index') }}" class="label label-primary pull-right">Back</a>
            </div>
            <div class="panel-body">
                <form action="{{ route('category.insert') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-sm-4" >Category Name</label>
                        <div class="col-sm-6">
                            <input required type="text" name="cat_name" id="cat_name" class="form-control" value="{{ old('cat_name') }}">
                        </div>
                    </div>
					
                    <div class="form-group">
                        <label class="control-label col-sm-4" >Category Details</label>
                        <div class="col-sm-6">
                            <textarea required rows="10" name="cat_description" id="cat_description" class="form-control">{{ old('cat_description') }}</textarea>
                        </div>
                    </div>
					
					<div class="form-group">
                        <label class="control-label col-sm-4" >Category Image</label>
                        <div class="col-sm-6">
                            <input required type="file" name="cat_photo" id="cat_photo" class="form-control" onchange="loadFile(event)">
							<img id="output"/>
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
                            <input type="submit" class="btn btn-default" value="Add Category" />
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
	output.width = 100;
	output.height = 80;
    output.src = URL.createObjectURL(event.target.files[0]);
  };
</script>