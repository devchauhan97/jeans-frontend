<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Currency extends Model
{
    protected $guard = "admins";
  
	protected $table = 'currencies';

	protected $guarded = ['currencies_id'];

	//use user id of admin
	protected $primaryKey = 'currencies_id'; 
}
