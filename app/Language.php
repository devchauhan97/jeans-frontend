<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Language extends Model
{
  
  
	protected $table = 'languages';

	protected $guarded = ['languages_id'];

	//use user id of admin
	protected $primaryKey = 'languages_id'; 
}
