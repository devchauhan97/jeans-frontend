<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class WhosOnline extends Model
{
    protected $guard = "customers";
	
	protected $table = 'whos_online';

	protected $guarded = ['customer_id'];
	
	protected $primaryKey = 'customer_id';
}
