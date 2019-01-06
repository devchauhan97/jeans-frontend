<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class NewsCategory extends Model
{
  
  
	protected $table = 'news_categories';

	protected $guarded = ['categories_id'];

	//use user id of admin
	protected $primaryKey = 'categories_id'; 
}
