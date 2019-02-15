<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsOptionsValuesToProductsOption extends Model
{
    //
    protected $fillable = ['products_options_id', 'products_options_values_id'];
    protected $primaryKey = 'products_options_values_to_products_options_id';

    public function products_options_values(){

		return $this->hasOne(ProductsOptionsValue::class,'products_options_values_id','products_options_values_id');
	} 
    
}
