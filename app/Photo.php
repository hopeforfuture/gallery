<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';
	
	protected $fillable = [
        'album_id', 'title', 'photo_name', 'is_active', 'photo_status'
    ];
	
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'modified_at';
	
	public function album()
	{
		return $this->belongsTo('App\Album', 'album_id');
	}
}
