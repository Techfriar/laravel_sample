<?php

namespace App\Support\Managers;

use Illuminate\Support\Facades\Storage;

class CommonSupportManager
{
    /**
     * Upload User Image
     *
     * @param String $file
     * @param String $name
     * @param String $path
     * @return $file|false
     */
    public function uploadPublicFile($file, $path)
    {
        $file = Storage::disk('public')->put($path, $file);
        if (!empty($file)) {
            return $file;
        }
        return false;
    }
}