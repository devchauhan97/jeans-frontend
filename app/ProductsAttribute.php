<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class ProductsAttribute extends Model
{
    protected $table = 'products_attributes';

	protected $guarded = ['products_attributes_id'];

	//use user id of admin
	protected $primaryKey = 'products_attributes_id'; 


	public function products_option()
	{
		return $this->hasOne(ProductsOption::class,'products_options_id','options_id');
	} 
	public function default_products_option()
	{
		return $this->hasOne(ProductsOption::class,'products_options_id','options_id');
	}

	public function default_products_options_values()
	{
		return $this->hasOne(ProductsOptionsValue::class,'products_options_values_id','options_values_id');
	}

	public function products_options_values()
	{
		return $this->hasOne(ProductsOptionsValue::class,'products_options_values_id','options_values_id');
	}
}
