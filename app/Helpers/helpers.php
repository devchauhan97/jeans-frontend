<?php

 
use Illuminate\Support\Facades\Storage;
 
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
    $file_ftp = Storage::disk('ftp')->put($uploadImage, $file_local);	
}

function getFtpImage($imagepath)
{

	if(Storage::disk('ftp')->exists($imagepath)){

		//$image = Storage::disk('ftp')->get($imagepath);
	}
 
    if(env('FTP_HOST'))
        $image='http://'.env('FTP_HOST').'/jeans-images/file.png';
    else
        $image='http://localhost'.'/jeans-images/'.$imagepath;
    return $image;
}