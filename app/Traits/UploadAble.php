<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Trait UploadAble
 * @package App\Traits
 */
trait UploadAble
{
    /**
     * * Upload File Method * *
     * @param UploadedFile $file
     * @param null $folder
     * @param null $filename
     * @param null $disk
     * @return false|string
     */
    public function upload_file(UploadedFile $file, $folder = null,  $file_name = null, $disk = 'public')
    {
        if (!Storage::directories($disk.'/'.$folder)) {
			Storage::makeDirectory($disk.'/'.$folder,0777, true); //if directory not exist then make the directory
        }
        
        $filenameWithExt = $file->getClientOriginalName(); // Get filename with extension like index.jpg
        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME); // Get just filename  like index          
        $extension       = $file->getClientOriginalExtension(); // Get just extension like .jpg

        $fileNameToStore = !is_null($file_name) ? str_replace(' ', '-', $file_name).'.'.$extension : str_replace(' ', '-', $filename).'-'.rand(111111,999999).'.'.$extension; //Filename to store  like index1545gfh5465.jpg                
        $file->storeAs($folder,$fileNameToStore,$disk); //store file in targetted folder 
        return $fileNameToStore;
    }
    
    /**
     * ! Delete File Method ! 
     * @param string $filename
     * @param string $folder
     * @param string $disk
     * @return true|false
     */
    public function delete_file($filename,$folder,$disk = 'public')
    {
        if(Storage::exists($disk.'/'.$folder.$filename))
        {
            Storage::disk($disk)->delete($folder.$filename);
            return TRUE;
        }
        return false;
    }

    public function upload_base64_image($image_64,$folder)
    {

        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

        
        $replace = substr($image_64, 0, strpos($image_64, ',')+1); 

        // find substring fro replace here eg: data:image/png;base64,

        $image = str_replace($replace, '', $image_64); 

        $image = str_replace(' ', '+', $image); 

        $imageName = Str::random(10).'.'.$extension;

        Storage::disk('public')->put($folder.$imageName, base64_decode($image));
        return $imageName;

    }
}
