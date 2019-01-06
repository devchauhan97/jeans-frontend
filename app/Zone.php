<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Zone extends Model
{
     protected $guard = "admins";
  
	protected $table = 'zones';

	protected $guarded = ['zone_id'];

	//use user id of admin
	protected $primaryKey = 'zone_id';
}
