<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class TaxRate extends Model
{
    protected $guard = "admins";
  
	protected $table = 'tax_rates';

	protected $guarded = ['tax_rates_id'];

	//use user id of admin
	protected $primaryKey = 'tax_rates_id';
}
