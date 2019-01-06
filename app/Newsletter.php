<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Newsletter extends Model
{
    
  
	protected $table = 'newsletters';

	protected $guarded = ['newsletters_id'];

	//use user id of admin
	protected $primaryKey = 'newsletters_id'; 
}
