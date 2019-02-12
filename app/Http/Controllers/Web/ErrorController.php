<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ErrorController extends DataController
{
    //
    public function notFound()
    {
    	$title = array('pageTitle' => 'Not Found');
    	$result['commonContent'] = $this->commonContent();
    	return view("errors.404", $title)->with('result', $result);

    }
    public function fatal() 
    {

    	echo 'fatal';
    }
}
