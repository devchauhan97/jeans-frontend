<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UpsShipping extends Model
{
    protected $table = 'ups_shipping';

	protected $guarded = ['ups_id'];

	//use user id of admin
	protected $primaryKey = 'ups_id'; 
}
