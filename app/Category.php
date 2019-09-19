<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
	
	protected $fillable = [
        'cat_name', 'cat_description', 'cat_photo', 'createdby', 'is_active'
    ];
	
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'modified_at';
	
	public function albums()
	{
		return $this->hasMany('App\Album', 'category_id');
	}
}
