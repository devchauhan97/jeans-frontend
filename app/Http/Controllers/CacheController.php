<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;
class CacheController extends Controller
{
   public  function index() {
 
		$exitCode = Artisan::call('config:cache');

		$exitCode = Artisan::call('optimize');
		$exitCode = Artisan::call('route:cache');

		return "Cache is cleared";
    }
 
}
