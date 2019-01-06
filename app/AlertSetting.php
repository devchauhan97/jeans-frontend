<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AlertSetting extends Model
{
     protected $guard = "admins";
	
	protected $table = 'alert_settings';
	
	protected $guarded = ['alert_id'];

	protected $primaryKey = 'alert_id';  
}
