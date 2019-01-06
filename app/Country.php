<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Country extends Model
{
    protected $guard = "admins";
  
	protected $table = 'countries';

	protected $guarded = ['countries_id'];

	//use user id of admin
	protected $primaryKey = 'countries_id'; 
}
