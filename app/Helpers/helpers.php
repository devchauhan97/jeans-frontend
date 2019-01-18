<?php

 
use Illuminate\Support\Facades\Storage;
 
 //create random password for social links
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

    $file_ftp = Storage::disk('ftp')->put('images/'.$uploadImage, $file_local);	
}

function getFtpImage($imagepath)
{

    $image='http://'.config('FTP_HOST').'/images/not-found.png';
    if(Storage::disk('ftp')->exists('/images/'.$imagepath)){
        //$image = Storage::disk('ftp')->get($imagepath);
        $host =Config::get('filesystems.disks.ftp.host');
        $image ='http://'.$host.'/images/'.$imagepath;

    }
  
    return $image;
}