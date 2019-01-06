<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Manufacturer extends Model
{
    protected $guard = "admins";
  
	protected $table = 'manufacturers';

	protected $guarded = ['manufacturers_id'];

	//use user id of admin
	protected $primaryKey = 'manufacturers_id'; 
}
