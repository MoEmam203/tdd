<?php

namespace App\Services;

use ZipArchive;

class Zipper{
    public static function toZip($jsonFileName)
    {
        $zip = new ZipArchive();
        $zipFileName = storage_path('/app/public/temp/' . now()->timestamp . '-tasks.zip');
        if($zip->open($zipFileName,ZipArchive::CREATE) === true){
            $filePath = storage_path('app/public/temp/' . $jsonFileName);
            $zip->addFile($filePath,$jsonFileName);
        }
        $zip->close();

        return $zipFileName;
    }
}