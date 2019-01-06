<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Device extends Model
{
   
	
	protected $table = 'devices';

	protected $guarded = ['id'];
	
	protected $primaryKey = 'id';
}
