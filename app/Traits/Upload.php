<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait Upload
{
    public function uploadFile(Request $request, $fieldname = 'profile_photo', $directory = 'uploads', $disk = 'public')
    {
        if ($request->hasFile($fieldname)) {
            $file = $request->file($fieldname);
            $filename = $file->getClientOriginalName();
            $path = $file->storeAs($directory, $filename, $disk);
            return $path;
        }
        return null;
    }


}
