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
                Add  New Photos <a href="{{ route('album.index') }}" class="label label-primary pull-right">Back</a>
				
				
            </div>
            <div class="panel-body" style="overflow:scroll;">
                <form id="frmphoto"  action="{{ route('album.upload.submit', base64_encode($album_id)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                    {{ csrf_field() }}
				  <div class="tr_clone" id="addon">  
						<div class="form-group row" >
						
						  <div class="col-xs-2">
							<label for="ex1">Title</label>
							<input required class="form-control title"  type="text" name="title[]">
						  </div>
						  
						  <div class="col-xs-2">
							<label for="ex2">Photo</label><img class="imgstatus" style="display:none;" width="40" height="30" />
							<input required class="form-control photo" type="file" name="images[]">
						  </div>
						  
						</div>
						<div class="col-md-2 col-sm-2 col-xs-2" style="float:right;margin-top:-42px;">
							
							<a href="Javascript:void(0)" class="plus">
								<img src='{{ asset("img/plus.jpg")}}' width="40" height="30" />
							</a>
							<a href="Javascript:void(0)" class="minus" >
								<img  src='{{ asset("img/remove.png")}}' width="40" height="30" />
							</a>
						</div>
						  
				 </div>
				 <div class="form-group" >
                        <label for="CaptchaCode" class="control-label col-sm-4" ></label>
                        <div class="col-xs-2 col-sm-3">
                            {!! captcha_image_html('ContactCaptcha') !!}
                                <input id="CaptchaCode" type="text" class="form-control" name="CaptchaCode"  required autofocus>
                        </div>
                  </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
							<input type="hidden" id="correct" value='{{ asset("img/ok.jpeg") }}' />
							<input type="hidden" id="wrong" value='{{ asset("img/wrong.png") }}' />
                            <input type="submit" class="btn btn-default" value="Save" onclick="Javascript: return formvalidate();" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
 $.ajaxSetup({

	headers: {

		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}

});
$(document).ready(function(){
	
	var allowed_ext = ['.jpeg', '.png', '.gif', '.jpg'];
	var filename = '';
	var ext = '';
	var correct_src = $("#correct").val();
	var wrong_src = $("#wrong").val();
	
	$("body").on("click", ".plus", function(){
		var $tr = $(this).closest('.tr_clone');
		var $clone = $tr.clone();         
		$clone.find('input').val(''); 
		$tr.after($clone);
	});
	
	$("body").on("click", ".minus", function() {
		var div_count = $('div.tr_clone').length;
		if(div_count > 1)
		{
			$(this).closest('.tr_clone').remove(); 
		}       
	});
	
	$("body").on("change", ".photo", function(){
		filename = $(this).val();
		ext = filename.substr(filename.lastIndexOf('.')).toLowerCase();
		
		if(allowed_ext.includes(ext))
		{
			$(this).closest(".col-xs-2").find(".imgstatus").show();
			$(this).closest(".col-xs-2").find(".imgstatus").attr('src', correct_src);
		}
		else
		{
			$(this).closest(".col-xs-2").find(".imgstatus").show();
			$(this).closest(".col-xs-2").find(".imgstatus").attr('src', wrong_src);
		}
	});
	
});

function formvalidate()
{
	var flag = true;
	var allowed_ext = ['.jpeg', '.png', '.gif', '.jpg'];
	var filename = '';
	var ext = '';
	var title = '';
	var err_title = '';
	var err_file = '';
	var err_ext = '';
	var err_msg = '';
	var separator = "<br/>";
	
	$(".title").each(function(){
		title = $.trim($(this).val());
		if(title == '')
		{
			flag = false;
			err_title = 'One or more title field is blank.' + separator;
		}
	});
	
	$(".photo").each(function(){
		filename = $.trim($(this).val());
		if(filename == '')
		{
			flag = false;
			err_file = 'One or more file field is empty.' + separator;
		}
		else
		{
			ext = filename.substr(filename.lastIndexOf('.')).toLowerCase();
			
			if(!allowed_ext.includes(ext))
			{
				flag = false;
				err_ext = 'One or more file field is invalid.';
			}
		}
	});
	
	err_msg = err_title + err_file + err_ext;
	
	if(flag)
	{
		return true;
	}
	else
	{
		$(".modal-body").html(err_msg);
		$("#myModal").modal();
		return false;
	}
}

</script>
@endsection