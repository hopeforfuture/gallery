<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Image;
use Session;

class CategoryController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth:admin');
	}
	
	public function index(Request $request)
	{
		$categories = Category::where('is_active', '=', '1')->orderBy('id', 'DESC')->paginate(10);
		return view('categories.index',compact('categories'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
	}
	
	public function create()
	{
		return view('categories.create');
	}
	
	public function store(Request $request)
	{
		$this->validate($request, [
		
			'cat_name'=> 'required',
			'cat_description'=>'required',
			'cat_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'CaptchaCode' => 'required|valid_captcha'
		]);
		
		$user = auth()->user();
		
		$postdata = $request->input();
		$category = new Category($postdata);
		
		if($request->hasFile('cat_photo'))
		{
			$file = $request->file('cat_photo');
			$filename =  time().'.'.$file->getClientOriginalExtension();
			$destinationPath = public_path().'/uploads/categories/large/' ;
			$destinationPath_thumb = public_path().'/uploads/categories/thumb/' ;
            
			$img = Image::make($file->getRealPath());
			
			$img->resize(120, 100, function ($constraint) {
				$constraint->aspectRatio();
			})->save($destinationPath_thumb.$filename);
			
			$img->save($destinationPath.$filename);
			
			$category->cat_photo = $filename;
		}
		$category->createdby = $user->id;
		$category->save();
		Session::flash('success_msg', 'Category Added successfully.');
		return redirect()->route('category.index');
	}
	
	public function edit($id)
	{
		$category = Category::find($id);
		return view('categories.edit', ['category'=>$category]);
	}
	
	public function update($id, Request $request)
	{
		$this->validate($request, [
		
			'cat_name'=> 'required',
			'cat_description'=>'required',
			'cat_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'CaptchaCode' => 'required|valid_captcha'
		]);
		
		$user = auth()->user();
		$postdata = $request->input();
		
		$category = Category::find($id);
		
		if($request->hasFile('cat_photo'))
		{
			$file = $request->file('cat_photo');
			$filename =  time().'.'.$file->getClientOriginalExtension();
			$destinationPath = public_path().'/uploads/categories/large/' ;
			$destinationPath_thumb = public_path().'/uploads/categories/thumb/' ;
            
			$img = Image::make($file->getRealPath());
			
			$img->resize(120, 100, function ($constraint) {
				$constraint->aspectRatio();
			})->save($destinationPath_thumb.$filename);
			
			$img->save($destinationPath.$filename);
			
			$postdata['cat_photo'] = $filename;
			
			$old_image_thumb = $destinationPath.$category->cat_photo;
			$old_image = $destinationPath_thumb.$category->cat_photo;
			
			@unlink($old_image);
			@unlink($old_image_thumb);
		}
		
		Category::find($id)->update($postdata);
		Session::flash('success_msg', 'Category updated successfully.');
		return redirect()->route('category.index');
	}
	
	public function remove($id)
	{
		$category = Category::find($id);
		
		$category->is_active = '0';
		
		$category->save();
		
		Session::flash('success_msg', 'Category Deleted successfully.');
		
		return redirect()->route('category.index');
	}
}
