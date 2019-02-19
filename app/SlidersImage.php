<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class SlidersImage extends Model
{
  
  	protected $table = 'sliders_images';

	protected $guarded = ['sliders_id'];
	//use user id of admin
	protected $primaryKey = 'sliders_id'; 

	public function scopehomeSilder()
    {
       return $this->where('status', '=', '1')
				   ->where('expires_date', '>=', time())
				   ->select('sliders_id as id', 'sliders_title as title', 'sliders_url as url', 'sliders_image as image', 'type', 'sliders_title as title');
    }
}
