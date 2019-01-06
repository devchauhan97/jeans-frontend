<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActionRecorder extends Model
{
    protected $table = 'action_recorder';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
	
}
