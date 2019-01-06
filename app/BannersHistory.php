<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BannersHistory extends Model
{
     
	
	protected $table = 'banners_history';
	
	protected $guarded = ['banners_history_id'];

	protected $primaryKey = 'banners_history_id';
}
