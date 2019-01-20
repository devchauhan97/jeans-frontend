<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Category extends Model
{
    
	
	protected $table = 'categories';
	
	protected $guarded = ['categories_id'];

	protected $primaryKey = 'categories_id'; 

	public function parent() {
	    return $this->belongsTo(self::class, 'parent_id');
	}

	public function sub_categories() {
	    return $this->hasMany(self::class, 'parent_id','categories_id')->where('categories_status',1);
	}

	public function categories_description() {
	    return $this->hasOne(CategoryDescription::class,'categories_id');
	}

	public function products() {
	    return $this->hasMany(Product::class, 'categories_id');
	}
}
