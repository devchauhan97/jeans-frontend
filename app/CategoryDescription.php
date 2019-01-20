<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryDescription extends Model
{
    protected $table = 'categories_description';

	public function category() {
	    return $this->hasOne(Category::class, 'categories_id');
	}
}
