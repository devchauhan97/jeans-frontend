<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressFormat extends Model
{
    protected $table = 'address_format';
    protected $guarded = ['address_format_id'];
    protected $primaryKey = 'address_format_id';
}
