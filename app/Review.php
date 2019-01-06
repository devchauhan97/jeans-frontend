<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Review extends Model
{
    protected $guard = "customers";
  
	protected $table = 'reviews';

	protected $guarded = ['reviews_id'];

	//use user id of admin
	protected $primaryKey = 'reviews_id';  
}
