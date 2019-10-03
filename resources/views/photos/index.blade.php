@extends('layouts.user-home')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @if(Session::has('success_msg'))
        <div class="alert alert-success">{{ Session::get('success_msg') }}</div>
        @endif
    <!-- Posts list -->
    @if(!empty($photos))
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Photos List </h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ route('album.upload', $album_id) }}"> Add New Images</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <table class="table table-striped task-table">
                    <!-- Table Headings -->
                    <thead>
						<th>Serial No</th>
                        <th>Album Name</th>
                        <th>Photo Title</th>
						<th>Photo</th>
						<th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </thead>
    
                    <!-- Table Body -->
                    <tbody>
                    @foreach($photos as $photo)
                        <tr>
							<td class="table-text">
								<div>{{++$i}}</div>
							</td>
                            <td class="table-text">
                                <div>{{$photo->album->album_name}}</div>
                            </td>
                            <td class="table-text">
								<div>{{$photo->title}}</div>
                            </td>
							
							<td class="table-text">
                                <div>
									<a href='{{ asset("uploads/photos/large/$photo->photo_name")}}' data-lightbox="{{$photo->album->album_name}}">
										<img src='{{ asset("uploads/photos/thumb/$photo->photo_name")}}' />
									</a>
									
								</div>
                            </td>
							
							<td class="table-text">
								<div>
									@if($photo->photo_status == 1)
										Public
									@else
										Private
									@endif
								</div>
                            </td>
							
                            <td class="table-text">
                                <div>{{$photo->created_at}}</div>
                            </td>
                            <td>
                               <a href="{{ route('album.photo.edit', base64_encode($photo->id)) }}" class="label label-warning">Edit</a>
                                <a href="{{ route('album.photo.delete', base64_encode($photo->id)) }}" class="label label-danger" onclick="return confirm('Are you sure to delete?')">Delete</a> 
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
		 <nav>
			<ul class="pagination justify-content-end">
				 {{$photos->links('vendor.pagination.bootstrap-4')}}
			 </ul>
		</nav>
    @endif
    </div>
</div>
<script src="{{ URL::asset('js/lightbox.js') }}"></script>
<script>
	$("body").on("click", ".long-link", function(){
		$(this).closest("td").find("div.short-str").hide();
		$(this).closest("td").find("div.long-str").show();
	});
	$("body").on("click", ".short-link", function(){
		$(this).closest("td").find("div.short-str").show();
		$(this).closest("td").find("div.long-str").hide();
	});
</script>
@endsection
