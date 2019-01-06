<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class ProductsOption extends Model
{
    protected $table = 'products_options';

	protected $guarded = ['products_options_id'];

	//use user id of admin
	protected $primaryKey = 'products_options_id'; 
}
