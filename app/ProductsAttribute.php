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
}
