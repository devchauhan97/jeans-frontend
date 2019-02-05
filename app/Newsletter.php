<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Newsletter extends Model
{
    	
 	protected 	$connection = 'mysql2';

// 	protected $table = 'newsletters';

// 	protected $guarded = ['newsletters_id'];

// 	//use user id of admin
// 	protected $primaryKey = 'newsletters_id'; 

 	protected 	$table = 'tipsy_newslatter';
 	protected 	$fillable =['email'  ,'company_name'  ,'page_url'  ,'city' ,'ip' ,'date_added' ,'subscribe'];
 	public 		$timestamps = false;

}
