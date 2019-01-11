<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
 	/**
     * The attributes that are mass assignable.
     *
     * @var string
     */
	//use Notifiable;

	protected $guard = "customers";
	
	protected $table = 'customers';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'customers_firstname' ,
                            'customers_lastname' ,
                            'email' ,
                            'isActive',
                            'password',
                            'customers_firstname',
                            'customers_lastname',
                            'customers_fax',
                            'customers_newsletter',
                            'customers_telephone',
                            'customers_gender',
                            'customers_dob',
                            'customers_picture' 

                        ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
	
	//use user id of admin
	protected $primaryKey = 'customers_id';
	
	//public $table = true;
	
}
