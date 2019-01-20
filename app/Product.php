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



	public  function products_description()
	{

		return $this->hasOne(ProductsDescription::class,'products_id');
	}
	public  function special()
	{

		return $this->hasMany(Special::class,'products_id');
	}

	public  function scopeGetProduct($query)
	{

		return $query->where('products_quantity','>','0')
							->leftJoin('products_description','products_description.products_id','=','products.products_id');
	}
}
