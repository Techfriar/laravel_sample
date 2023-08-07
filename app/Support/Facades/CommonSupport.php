<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool uploadPublicFile(String $file,String $path))
 * 
 * @see \App\Support\Managers\CommonSupportManager
 */
class CommonSupport extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "commonSupport";
    }
}