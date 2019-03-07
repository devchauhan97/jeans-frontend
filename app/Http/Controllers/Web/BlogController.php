<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Blog;
use App\BlogDescription;

class BlogController extends Controller
{
    public function index() 
	{

	    //return redirect('/404')->withErrors('Blogs under constraction.');
	}
	
	public function getDetail(Request $request) 
	{	
		return redirect('/404')->withErrors($request->blogs_id.'Blogs under constraction.');
	}
}
