<?php

namespace App\Utils;

use finfo;
use Illuminate\Support\Facades\Storage;

class Base64
{
    public static function getDecode($directory, $data)
    {


        if (!Storage::disk('public')->exists($directory)) {
            // If it doesn't exist, create it
            Storage::disk('public')->makeDirectory($directory);
        }
        $decodedData = base64_decode($data);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $decodedData);
        // dd($mimeType);

        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
            'text/plain' => 'txt',
            'text/html' => 'html',
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        ];

        $defaultExtension = 'txt'; // Default extension if the mime type is not recognized

        $extension = $extensions[$mimeType];
        $filename =   time() . '_' . uniqid() . '.' . $extension;
        $file_path = $directory . $filename;
        Storage::disk('public')->put($file_path, $decodedData);
        return [
            'decoded_data' => $decodedData,
            'file_path' => $file_path
        ];
    }
}
