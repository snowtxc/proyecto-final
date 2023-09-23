<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;


class FileHelper
{
    public static function uploadFile($file, $folder, $fileName)
    {
        $path = $file->storeAs($folder,$fileName);
        return $path;
    }

    public static function deleteFile($filePath)
    {
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }

    public static function getRealPath($path){
       return Storage::url($path);
    }

}
