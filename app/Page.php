<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Page extends Model
{
     
  
	protected $table = 'pages';

	protected $guarded = ['page_id'];

	//use user id of admin
	protected $primaryKey = 'page_id'; 
}
