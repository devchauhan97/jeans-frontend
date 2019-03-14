<?php

 
use Illuminate\Support\Facades\Storage;
 
 //create random password for social links
use Illuminate\Support\Facades\Cache;
use App\Order;
function createRandomPassword() { 
    $pass = substr(md5(uniqid(mt_rand(), true)) , 0, 8);    
    return $pass; 
}
    
function store(Request $request)
{
    if($request->hasFile('newImage')) {
         
        //get filename with extension
        $filenamewithextension = $request->file('newImage')->getClientOriginalName();
 
        //get filename without extension
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
 
        //get file extension
        $extension = $request->file('newImage')->getClientOriginalExtension();
 
        //filename to store
        $filenametostore = $filename.'_'.uniqid().'.'.$extension;
 
        //Upload File to external server
        Storage::disk('ftp')->put($filenametostore, fopen($request->file('newImage'), 'r+'));
 		return $filenametostore ;
        //Store $filenametostore in the database
    }
 
    
}

function getZoneCountry($zone_country_id){

    $zone = DB::table('zones')->where('zone_country_id', $zone_country_id)->get(); 
    
    return $zone;

}

function storeImage($uploadImage)
{
	$file_local = Storage::disk('public')->get($uploadImage);
    $uploadImage= 'images/'.$uploadImage;
    $file_ftp = Storage::disk('ftp')->put($uploadImage, $file_local);	
}

function getFtpImage($imagepath)
{

    
    $host =Config::get('filesystems.disks.ftp.host');

    if(env('APP_ENV') == 'local')
        $image ='http://'.$host.'/images/'.$imagepath;
    else 
      $image ='https://www.'.$host.'/'.$imagepath;

    /*if(Storage::disk('ftp')->exists('/images/'.$imagepath)){
        //$image = Storage::disk('ftp')->get($imagepath);

    }else{
        $image=asset('images/not-found.png');
    }*/
    return $image;
}

function removeOldCache($session_id)
{
    Cache::forget('basket_cart'.$session_id);
}

function makeQueryParameter($arr=[])
{   
    $query_param = '';
    foreach ( $arr as $key => $value ) {
        if( $query_param )
            $query_param .= '&';
        $query_param .= $key.'='.$value;
    }
    return $query_param ;
}


 function getNextOrderNumber()
{
    // Get the last created order
    $lastOrder = Order::orderBy('created_at', 'desc')->first();

    if ( ! $lastOrder )
        // We get here if there is no order at all
        // If there is no number set it to 0, which will be 1 at the end.

        $number = 0;
    else 
        $number = substr($lastOrder->order_id, 3);

    // If we have ORD000001 in the database then we only want the number
    // So the substr returns this 000001

    // Add the string in front and higher up the number.
    // the %05d part makes sure that there are always 6 numbers in the string.
    // so it adds the missing zero's when needed.
 
    return 'ORDE-' . sprintf('%06d', intval($number) + 1);
}