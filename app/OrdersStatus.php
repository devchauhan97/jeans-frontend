<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdersStatus extends Model
{
    protected $table = 'orders_status';

	protected $guarded = ['orders_status_id'];

	//use user id of admin
	protected $primaryKey = 'orders_status_id';
}

