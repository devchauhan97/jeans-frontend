<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class ProductsImage extends Model
{
    protected $table = 'products_images';

	protected $guarded = ['id'];

	//use user id of admin
	protected $primaryKey = 'id'; 
}
