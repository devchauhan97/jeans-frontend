<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlateRate extends Model
{
    protected $table = 'flate_rate';

	protected $guarded = ['id'];
	
	protected $primaryKey = 'id';
}
