<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    //protected $fillable = ['title', 'sort_description', 'image', 'status' ];
    protected $primaryKey = 'blogs_id';
    
    protected $guarded = [];

    public static function  blogDescriptions()
    {
    	return self::join('blog_descriptions','blog_descriptions.blogs_id','blogs.blogs_id')
		    		->where('blogs.status',1)
		    		->orderBy('blogs.blogs_id','desc')
		    		->limit(10);
    }
}
