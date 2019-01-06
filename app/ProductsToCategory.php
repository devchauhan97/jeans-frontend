<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class ProductsToCategory extends Model
{
    
  
	protected $table = 'products_to_categories';

	protected $guarded = ['products_id'];

	//use user id of admin
	protected $primaryKey = 'products_id';
}
