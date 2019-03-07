<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    protected $guarded = ['product_tag_id'];

	//use user id of admin
	protected $primaryKey = 'product_tag_id'; 


	public static function homeOccasionTags()
	{
		$result = self::where('occasions',1)->get();
		return $result;
	}
	
}
