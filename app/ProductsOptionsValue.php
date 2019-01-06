<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ProductsOptionsValue extends Model
{
   protected $table = 'products_options_values';

	protected $guarded = ['products_options_values_id'];

	//use user id of admin
	protected $primaryKey = 'products_options_values_id'; 
}
