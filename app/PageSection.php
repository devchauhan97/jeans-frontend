<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    protected $guarded=[];


    public function scopepageSectionTop(){

    	return $this->where(['position'=>'top','status' => '1'])
								   ->where('expires_date', '>', time());
    }
    

    public function scopepageSectionCenter(){

    	return $this->where(['position'=>'center','status' => '1'])
								   ->where('expires_date', '>', time());
    }

    public function scopepageSectionBottom(){

    	return $this->where(['position'=>'bottom','status' => '1'])
								   ->where('expires_date', '>', time());
    }

}
