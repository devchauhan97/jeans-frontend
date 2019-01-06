<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Special extends Model
{
   protected $table = 'specials';

	protected $guarded = ['specials_id'];

	//use user id of admin
	protected $primaryKey = 'specials_id'; 
}
