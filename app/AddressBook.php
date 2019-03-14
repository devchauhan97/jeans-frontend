<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
     protected $table = 'address_book';
    protected $guarded = ['address_book_id'];
    protected $primaryKey = 'address_book_id';

    public static function getShippingAddress($address_id = null)
	{	
		$addresses = self::
					 leftJoin('countries', 'countries.countries_id', '=' ,'address_book.entry_country_id')
					->leftJoin('zones', 'zones.zone_id', '=' ,'address_book.entry_zone_id')
					->leftJoin('customers', 'customers.customers_default_address_id', '=' , 'address_book.address_book_id')
					->select(
							'address_book.address_book_id as address_id',
							'address_book.entry_gender as gender',
							'address_book.entry_company as company',
							'address_book.entry_firstname as firstname',
							'address_book.entry_lastname as lastname',
							'address_book.entry_street_address as street',
							'address_book.entry_suburb as suburb',
							'address_book.entry_postcode as postcode',
							'address_book.entry_city as city',
							'address_book.entry_state as state',
							'address_book.entry_phone_no as phone_no',
							
							'countries.countries_id as countries_id',
							'countries.countries_name as country_name',
							
							'zones.zone_id as zone_id',
							'zones.zone_code as zone_code',
							'zones.zone_name as zone_name',
							'customers.customers_default_address_id as default_address'
							);
		if( auth()->guard('customer')->check() ) {
					 
			$addresses->where('address_book.customers_id', auth()->guard('customer')->user()->customers_id);
		}
		
		if(!empty($address_id)){
			$addresses->where('address_book_id', '=', $address_id);
		}
		$result = $addresses->get();
		
		return $result;
					
	}
}
