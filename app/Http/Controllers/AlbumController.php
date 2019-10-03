<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Album;
use App\Photo;
use Image;
use Session;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class AlbumController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function isalbumauthenticate($album_id)
	{
		$album = Album::find($album_id);
		$user = auth()->user();
		
		if($user->id == $album->user_id)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function index(Request $request)
	{
		$user = auth()->user();
		
		$albums = Album::where([
			   ['is_active', '=', '1'],
			   ['user_id', '=', $user->id]
			])->orderBy('id', 'DESC')->paginate(10);
			
		return view('album.index',compact('albums'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
	}
	
    public function create()
	{
		$categories = Category::where('is_active', '=', '1')->orderBy('cat_name', 'ASC')->get();
		return view('album.create', ['categories'=>$categories]);
	}
	
	public function store(Request $request)
	{
		$this->validate($request, [
		
			'album_name'=> 'required',
			'album_description'=>'required',
			'category_id'=>'required',
			'album_cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'CaptchaCode' => 'required|valid_captcha'
		]);
		$user = auth()->user();
		
		$postdata = $request->input();
		
		$album = new Album($postdata);
		
		if($request->hasFile('album_cover'))
		{
			$file = $request->file('album_cover');
			$filename =  time().'.'.$file->getClientOriginalExtension();
			$destinationPath = public_path().'/uploads/albums/large/' ;
			$destinationPath_thumb = public_path().'/uploads/albums/thumb/' ;
            
			$img = Image::make($file->getRealPath());
			
			$img->resize(120, 100, function ($constraint) {
				$constraint->aspectRatio();
			})->save($destinationPath_thumb.$filename);
			
			//$img->save($destinationPath.$filename);
			
			$file->move($destinationPath,$filename);
			
			$album->album_cover = $filename;
		}
		$album->user_id = $user->id;
		$album->save();
		Session::flash('success_msg', 'Album Added successfully.');
		return redirect()->route('album.index');
	}
	
	public function edit($id)
	{
		$album_id = base64_decode($id);
		
		if(!$this->isalbumauthenticate($album_id))
		{
			Session::flash('success_msg', 'Not an authorized user to view this album.');
			return redirect()->route('album.index');
		}
		
		$album = Album::find($album_id);
		$categories = Category::where('is_active', '=', '1')->orderBy('cat_name', 'ASC')->get();
		
		return view('album.edit', ['album'=>$album, 'categories'=>$categories]);
		
	}
	
	public function update($id, Request $request)
	{
		$this->validate($request, [
		
			'album_name'=> 'required',
			'album_description'=>'required',
			'category_id'=>'required',
			'album_cover' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'CaptchaCode' => 'required|valid_captcha'
		]);
		$user = auth()->user();
		
		$id = base64_decode($id);
		
		$postdata = $request->input();
		
		$album = Album::find($id);
		
		if($request->hasFile('album_cover'))
		{
			$file = $request->file('album_cover');
			$filename =  time().'.'.$file->getClientOriginalExtension();
			$destinationPath = public_path().'/uploads/albums/large/' ;
			$destinationPath_thumb = public_path().'/uploads/albums/thumb/' ;
            
			$img = Image::make($file->getRealPath());
			
			$img->resize(120, 100, function ($constraint) {
				$constraint->aspectRatio();
			})->save($destinationPath_thumb.$filename);
			
			//$img->save($destinationPath.$filename);
			
			$file->move($destinationPath,$filename);
			
			$postdata['album_cover'] = $filename;
			
			$old_image_thumb = $destinationPath.$album->album_cover;
			$old_image = $destinationPath_thumb.$album->album_cover;
			
			@unlink($old_image);
			@unlink($old_image_thumb);
		}
		
		Album::find($id)->update($postdata);
		Session::flash('success_msg', 'Album updated successfully.');
		return redirect()->route('album.index');
	}
	
	public function remove($id)
	{
		$id = base64_decode($id);
		if(!$this->isalbumauthenticate($id))
		{
			Session::flash('success_msg', 'Not an authorized user to view this album.');
			return redirect()->route('album.index');
		}
		
		$album = Album::find($id);
		
		$album->is_active = '0';
		$album->save();
		
		Session::flash('success_msg', 'Album deleted successfully.');
		return redirect()->route('album.index');
	}
	
	public function download($album_id)
	{
		$album_decoded_id = base64_decode($album_id);
		
		$photos = Photo::where([
			   ['is_active', '=', '1'],
			   ['album_id', '=', $album_decoded_id]
			])->orderBy('id', 'DESC')->get();
			
		$album_info = Album::find($album_decoded_id);
		
		$user = auth()->user();
			
		$dirsrc = public_path().'/uploads/photos/large/';
		
		$dirtmp = public_path().'/uploads/';
		
		$album_name = str_replace(' ','-',$album_info->album_name);
		
		$fname = strtolower(strstr($user->name,' ', true));
		
		$dir_to_be_created = $album_name.'-'.$fname.'-'.time();
			
		$photoslist = array();
		
		$filesrc = '';
		$filedest = '';
		$zipfile = $dir_to_be_created.'.'.'zip';
			
		if(!empty($photos))
		{
			mkdir($dirtmp.$dir_to_be_created);
			
			foreach($photos as $photo)
			{
				$filesrc = $dirsrc.$photo->photo_name;
				$filedest = $dirtmp.$dir_to_be_created.'/'.$photo->photo_name;
				if(file_exists($filesrc))
				{
					copy($filesrc,$filedest);
					$photoslist[] = $photo->photo_name;
				}
			}
			
			if(empty($photoslist))
			{
				rmdir($dirtmp.$dir_to_be_created);
				Session::flash('success_msg', 'Sorry, album is empty, could not be downloaded.');
				return redirect()->route('album.index');
			}
			
			
			$rootPath = realpath($dirtmp.$dir_to_be_created);
			$zip = new ZipArchive();
			$zip->open($zipfile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
			
			$files = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($rootPath),
				RecursiveIteratorIterator::LEAVES_ONLY
			);
			
			foreach ($files as $name => $file)
			{
				// Skip directories (they would be added automatically)
				if (!$file->isDir())
				{
					// Get real and relative path for current file
					$filePath = $file->getRealPath();
					$relativePath = substr($filePath, strlen($rootPath) + 1);

					// Add current file to archive
					$zip->addFile($filePath, $relativePath);
				}
			}
			
			// Zip archive will be created only after closing object
			$zip->close();
			//Remove the temporary created directory
			foreach($photos as $photo)
			{
				$tmpfile = $dirtmp.$dir_to_be_created.'/'.$photo->photo_name;
				if(file_exists($tmpfile))
				{
					@unlink($tmpfile);
				}
			}
			rmdir($dirtmp.$dir_to_be_created);
			//Download the zip file
			return response()->download($zipfile);
		}
		
		
	}
}
