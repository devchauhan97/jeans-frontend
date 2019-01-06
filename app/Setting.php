<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Setting extends Model
{
      protected $guard = "admins";
  
	protected $table = 'settings';

	protected $guarded = ['id'];

	//use user id of admin
	protected $primaryKey = 'id';
}
