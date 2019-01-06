<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Product extends Model
{
    
  
	protected $table = 'products';

	protected $guarded = ['products_id'];

	//use user id of admin
	protected $primaryKey = 'products_id'; 
}
