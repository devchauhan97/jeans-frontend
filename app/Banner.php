<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Banner extends Model
{
     protected $guard = "admins";
	
	protected $table = 'banners';
	
	protected $guarded = ['banners_id'];

	protected $primaryKey = 'banners_id'; 
}
