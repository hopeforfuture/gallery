<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Album;
use App\Photo;
use Image;
use Session;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function isAlbumORPhotoAuthenticate($data = array())
	{
		$user = auth()->user();
		$flag = true;
		
		if(array_key_exists('album_id', $data))
		{
			$album_id = $data['album_id'];
			$album = Album::find($album_id);
			if($user->id != $album->user_id)
			{
				$flag = false;
			}
		}
		else if(array_key_exists('photo_id', $data))
		{
			$photo_id = $data['photo_id'];
			$photo = Photo::find($photo_id);
			if($photo->album->user_id != $user->id)
			{
				$flag = false;
			}
		}
		
		return $flag;
		
	}
	
	public function upload($album_id)
	{
		$album_id = base64_decode($album_id);
		if(!$this->isAlbumORPhotoAuthenticate(['album_id'=>$album_id]))
		{
			Session::flash('success_msg', 'Not an authorized user to view this album.');
			return redirect()->route('album.index');
		}
		return view('photos.create', ['album_id'=>$album_id]);
	}
	
	public function saveimages($album_id, Request $request)
	{
		$this->validate($request, [
		
			'title.*'=> 'required',
			'images.*'=> 'required',
			'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'CaptchaCode' => 'required|valid_captcha'
		]);
		$album_id = base64_decode($album_id);
		$data = array();
		$postdata = $request->input('title');
		$title = current($postdata);
		$destinationPath = public_path().'/uploads/photos/large/' ;
		$destinationPath_thumb = public_path().'/uploads/photos/thumb/' ;
		
		if($request->hasfile('images'))
        {

            foreach($request->file('images') as $file)
            {
				$filename =  rand(10000, 10000000).'_'.time().'.'.$file->getClientOriginalExtension();
				
				$img = Image::make($file->getRealPath());
			
				$img->resize(120, 100, function ($constraint) {
					$constraint->aspectRatio();
				})->save($destinationPath_thumb.$filename);
				
				$file->move($destinationPath,$filename);
				
				$data = array('album_id'=>$album_id, 'title'=>$title, 'photo_name'=>$filename);
				
				$photo = new Photo($data);
				
				$photo->save();
				
				$title = next($postdata);
            }
        }
		
		echo "Images uploaded successfully.";
		die;
	}
}
