@extends('layouts.admin-home')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @if(Session::has('success_msg'))
        <div class="alert alert-success">{{ Session::get('success_msg') }}</div>
        @endif
    <!-- Posts list -->
    @if(!empty($categories))
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Category List </h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ route('category.create') }}"> Add New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <table class="table table-striped task-table">
                    <!-- Table Headings -->
                    <thead>
						<th>Serial No</th>
                        <th>Category Name</th>
                        <th>Category Details</th>
						<th>Category Image</th>
                        <th>Created</th>
                        <th>Action</th>
                    </thead>
    
                    <!-- Table Body -->
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
							<td class="table-text">
								<div>{{++$i}}</div>
							</td>
                            <td class="table-text">
                                <div>{{$category->cat_name}}</div>
                            </td>
                            <td class="table-text">
                                <div>{{ strip_tags($category->cat_description) }}</div>
                            </td>
							<td class="table-text">
                                <div><img src='{{ asset("uploads/categories/thumb/$category->cat_photo")}}' /></div>
                            </td>
                            <td class="table-text">
                                <div>{{$category->created_at}}</div>
                            </td>
                            <td>
                               <a href="{{ route('category.edit', $category->id) }}" class="label label-warning">Edit</a>
                                <a href="{{ route('category.delete', $category->id) }}" class="label label-danger" onclick="return confirm('Are you sure to delete?')">Delete</a> 
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
		 <nav>
			<ul class="pagination justify-content-end">
				 {{$categories->links('vendor.pagination.bootstrap-4')}}
			 </ul>
		</nav>
    @endif
    </div>
</div>
@endsection