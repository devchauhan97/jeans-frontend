<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Category extends Model
{
    
	
	protected $table = 'categories';
	
	protected $guarded = ['categories_id'];

	protected $primaryKey = 'categories_id';   
}
