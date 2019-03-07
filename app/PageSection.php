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

    	return $this->leftJoin('categories','categories.categories_slug','page_sections.sections_url')
                ->leftJoin('categories_description','categories_description.categories_id','categories.categories_id')
                    ->leftJoin('products','products.products_slug','page_sections.sections_url')
                    ->leftJoin('products_description','products_description.products_id','products.products_id')
                    ->select('page_sections.*','products_description.products_name','categories_description.categories_name','products_description.products_name')
                    ->where(['position'=>'bottom','status' => '1'])
            	    ->where('expires_date', '>', time());
    }

}
