<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
     protected $table = 'address_book';
    protected $guarded = ['address_book_id'];
    protected $primaryKey = 'address_book_id';
}
