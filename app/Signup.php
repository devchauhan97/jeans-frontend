<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Signup extends Model
{
   protected $table = 'customers';

   protected $fillable = ['email','password'];

   protected $hidden = ['password', 'remember_token'];
	//use user id of admin
	protected $primaryKey = 'customers_id';
}
