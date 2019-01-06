<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsToNewsCategory extends Model
{
    protected $table = 'news_to_news_categories';

	protected $guarded = ['news_id'];

	//use user id of admin
	protected $primaryKey = 'news_id'; 
}
