@extends('layouts.user-home')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @if(Session::has('success_msg'))
        <div class="alert alert-success">{{ Session::get('success_msg') }}</div>
        @endif
    <!-- Posts list -->
    @if(!empty($albums))
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Album List </h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ route('album.create') }}"> Add New</a>
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
                        <th>Album Details</th>
						<th>Cover Image</th>
						<th>Album Category</th>
                        <th>Created</th>
                        <th>Action</th>
                    </thead>
    
                    <!-- Table Body -->
                    <tbody>
                    @foreach($albums as $album)
                        <tr>
							<td class="table-text">
								<div>{{++$i}}</div>
							</td>
                            <td class="table-text">
                                <div>{{$album->album_name}}</div>
                            </td>
                            <td class="table-text">
							  @if(str_word_count($album->album_description) > 10)
									<div class="short-str">
										{{ strip_tags(Str::words($album->album_description, 10,'....')) }}
										<a class="long-link" href="Javascript:void(0);" >More</a>
									</div>
									<div class="long-str" style="display:none;">
										{{ strip_tags($album->album_description) }}
										<a class="short-link" href="Javascript:void(0);" >Less</a>
									</div>
								@else
									<div>{{ strip_tags($album->album_description) }}</div>
								@endif
                            </td>
							<td class="table-text">
                                <div><img src='{{ asset("uploads/albums/thumb/$album->album_cover")}}' /></div>
                            </td>
							<td class="table-text">
                                <div>{{$album->category->cat_name}}</div>
                            </td>
                            <td class="table-text">
                                <div>{{$album->created_at}}</div>
                            </td>
                            <td>
                               <a href="{{ route('album.edit', base64_encode($album->id)) }}" class="label label-warning">Edit</a>
                               <a href="{{ route('album.upload', base64_encode($album->id)) }}" class="label label-info">Upload Images</a>
                               <a href="{{ route('album.view.list', base64_encode($album->id)) }}" class="label label-info">View Images</a>
                                <a href="{{ route('album.delete', base64_encode($album->id)) }}" class="label label-danger" onclick="return confirm('Are you sure to delete?')">Delete</a> 
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
		 <nav>
			<ul class="pagination justify-content-end">
				 {{$albums->links('vendor.pagination.bootstrap-4')}}
			 </ul>
		</nav>
    @endif
    </div>
</div>
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
