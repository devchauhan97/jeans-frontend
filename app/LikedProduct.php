<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class LikedProduct extends Model
{
    protected $guard = "customers";
	
	protected $table = 'liked_products';

	protected $guarded = ['like_id']; 

	protected $primaryKey = 'like_id'; 
}
