<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class BasketAttribute extends Model
{
    protected $guard = "customers";
	
	protected $table = 'customers_basket_attributes';

	protected $guarded = ['customers_basket_attributes_id'];
	
	protected $primaryKey = 'customers_basket_attributes_id';
}
