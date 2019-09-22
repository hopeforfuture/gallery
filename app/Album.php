<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $table = 'albums';
	
	protected $fillable = [
        'album_name', 'album_description', 'album_cover', 'user_id', 'category_id', 'download_count', 'is_active'
    ];
	
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'modified_at';
	
	public function photo()
	{
		return $this->hasMany('App\Photo', 'album_id');
	}
	
	public function category()
	{
		return $this->belongsTo('App\Category', 'category_id');
	}
	
	public function users()
	{
		return $this->belongsTo('App\User', 'user_id');
	}
}
