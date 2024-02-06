<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;


class MTLHelper
{

    public static function FileUploaderHelper($request_file, $path=null, $custom_name = null)
    {
        if (!$custom_name) {
            $custom_name = time();
        }

        if (!$path) {
            $path = 'common/';
        }

        $name = $custom_name . '.' . $request_file->getClientOriginalExtension();

        // Store the file in the public directory with a custom name
        Storage::disk('public')->putFileAs($path, $request_file, $name);

        // Get the URL of the uploaded file
        return Storage::url($path . $name);

    }



}
