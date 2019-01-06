<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdersStatusHistory extends Model
{
   
	protected $table = 'orders_status_history';

	protected $guarded = ['orders_status_history_id'];

	//use user id of admin
	protected $primaryKey = 'orders_status_history_id';
}
