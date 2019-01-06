<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Unit extends Model
{
    protected $guard = "admins";
  
	protected $table = 'units';

	protected $guarded = ['unit_id'];

	//use user id of admin
	protected $primaryKey = 'unit_id';
}
