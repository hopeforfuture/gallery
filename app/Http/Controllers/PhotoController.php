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
			'images'=> 'required',
			'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
			'CaptchaCode' => 'required|valid_captcha'
		]);
		
		$album_id = base64_decode($album_id);
		$data = array();
		$postdata = $request->input('title');
		$photostatusarr = $request->only('imgpubpri');
		
		$photostatusarr = explode(",", $photostatusarr['imgpubpri'][0]);
		$title = current($postdata);
		$statusinfo = current($photostatusarr);
		$statusarr = explode("@", $statusinfo);
		
		
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
				
				$photo_status = ($statusarr[1] == "true") ? '1' : '0';
				
				$data = array('album_id'=>$album_id, 'title'=>$title, 'photo_name'=>$filename, 'photo_status'=>$photo_status);
				$photo = new Photo($data);
				$photo->save();
				
				$title = next($postdata);
				$statusinfo = next($photostatusarr);
				$statusarr = explode("@", $statusinfo);
            }
        }
		
		
		Session::flash('success_msg', 'Photos uploaded successfully.');
		return redirect()->route('album.view.list',base64_encode($album_id));
	}
	
	public function viewlist($album_id, Request $request)
	{
		$album_id = base64_decode($album_id);
		
		if(!$this->isAlbumORPhotoAuthenticate(['album_id'=>$album_id]))
		{
			Session::flash('success_msg', 'Not an authorized user to view this album.');
			return redirect()->route('album.index');
		}
		
		$user = auth()->user();
		
		$photos = Photo::where([
			   ['is_active', '=', '1'],
			   ['album_id', '=', $album_id]
			])->orderBy('id', 'DESC')->paginate(10);
			
		return view('photos.index',['photos'=>$photos, 'album_id'=>base64_encode($album_id)])
            ->with('i', ($request->input('page', 1) - 1) * 10);
		
		
	}
	
	public function edit($photo_id)
	{
		$photo_decoded_id = base64_decode($photo_id);
		
		if(!$this->isAlbumORPhotoAuthenticate(['photo_id'=>$photo_decoded_id]))
		{
			Session::flash('success_msg', 'Not an authorized user to view this photo.');
			return redirect()->route('album.index');
		}
		
		$photo = Photo::find($photo_decoded_id);
		
		return view('photos.edit', ['photo'=>$photo]);
	}
	
	public function update($photo_id, Request $request)
	{
		$this->validate($request, [
		
			'title'=> 'required',
			'photo_status'=>'required',
			'CaptchaCode' => 'required|valid_captcha'
		]);
		
		$photo_decoded_id = base64_decode($photo_id);
		
		$photoinfo = Photo::find($photo_decoded_id);
		
		$postdata = $request->input();
		
		Photo::find($photo_decoded_id)->update($postdata);
		Session::flash('success_msg', 'Photo updated successfully.');
		return redirect()->route('album.view.list', base64_encode($photoinfo->album->id));
	}
	
	public function remove($photo_id)
	{
		$photo_decoded_id = base64_decode($photo_id);
		
		$photoinfo = Photo::find($photo_decoded_id);
		
		if(!$this->isAlbumORPhotoAuthenticate(['photo_id'=>$photo_decoded_id]))
		{
			Session::flash('success_msg', 'Not an authorized user to delete this photo.');
			return redirect()->route('album.index');
		}
		
		$destinationPath = public_path().'/uploads/photos/large/' ;
		$destinationPath_thumb = public_path().'/uploads/photos/thumb/' ;
		
		$photo_large_src = $destinationPath.$photoinfo->photo_name;
		$photo_thumb_src = $destinationPath_thumb.$photoinfo->photo_name;
		
		@unlink($photo_large_src);
		@unlink($photo_thumb_src);
		
		Photo::find($photo_decoded_id)->delete();
		
		Session::flash('success_msg', 'Photo deleted successfully.');
		return redirect()->route('album.view.list', base64_encode($photoinfo->album->id));
	}
	
	
}
