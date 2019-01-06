<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Label extends Model
{
    protected $guard = "admins";
  
	protected $table = 'labels';

	protected $guarded = ['label_id'];

	//use user id of admin
	protected $primaryKey = 'label_id'; 
}
