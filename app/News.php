<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class News extends Model
{
   protected $guard = "admins";
  
	protected $table = 'news';

	protected $guarded = ['news_id'];

	//use user id of admin
	protected $primaryKey = 'news_id';   
}
