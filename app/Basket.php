<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Basket extends Model
{
   
	//protected $guard = "customers";
	
	protected $table = 'customers_basket';

	protected $guarded = ['customers_basket_id'];

	protected $primaryKey = 'customers_basket_id';
}
