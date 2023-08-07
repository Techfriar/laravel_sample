<?php
namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static String generateOTP()
 * @method static String createToken(User $user)
 * @method static String encrypt(String $password)
 *
 * @see \App\Support\Managers\AuthSupportManager
 */
class AuthSupport extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "authSupport";
    }
}