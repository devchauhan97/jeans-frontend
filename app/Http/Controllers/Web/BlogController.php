<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Blog;
use App\BlogDescription;
use Lang;
use DB;
class BlogController extends DataController
{
    public function index() 
	{

	    $title = array('pageTitle' => Lang::get("website.Sign Up"));
		$result = array();						
		$result['commonContent'] = $this->commonContent();	
		$result['blogs'] =  Blog::join('blog_descriptions','blog_descriptions.blogs_id','blogs.blogs_id')->get();
		return view("blog", $title)->with('result', $result); 
	}
	
	public function getDetail(Request $request) 
	{	
		$title = array('pageTitle' => Lang::get("website.Sign Up"));
		$result = array();						
		$result['commonContent'] = $this->commonContent();	

		$result['blogs_detail'] =  Blog::join('blog_descriptions','blog_descriptions.blogs_id','blogs.blogs_id')
									->join('administrators','administrators.myid','blogs.posted_by')
									->select("blogs.*","blog_descriptions.*","administrators.first_name", "administrators.last_name")
									->where('blogs.blogs_id',$request->blogs_id)->first();

		$result['blogs'] =  Blog::join('blog_descriptions','blog_descriptions.blogs_id','blogs.blogs_id')
							->where('blogs.posted_by',$result['blogs_detail']->posted_by)
							->orderBy('blogs.blogs_id','desc')
							
							->limit(3)
							->get();

		return view("blog-detail", $title)->with('result', $result); 
		 
	}
}
