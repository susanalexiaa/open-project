<?php 

namespace App\Helpers;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;


class ImageSaver
{

    public static function save($image)
    {
        $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
        $image = Image::make($image);
        
        $image->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();                 
        });
        
        $image->stream();

        $path = 'images/'.$fileName;
        Storage::put($path, $image);

        return $path;
    }
}