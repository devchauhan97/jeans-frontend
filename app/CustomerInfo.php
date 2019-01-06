<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomerInfo extends Model
{
    protected $guard = "customers";
	
	protected $table = 'customers_info';

	protected $guarded = ['customers_info_id'];
	
	protected $primaryKey = 'customers_info_id';
}
