<?php

 
use Illuminate\Support\Facades\Storage;
 
 //create random password for social links
use Illuminate\Support\Facades\Cache;
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
      $image ='http://'.$host.'/'.$imagepath;

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