<?php

namespace App\Support\Managers;
use App\Models\User;

class AuthSupportManager
{
    /**
     * Encrypt given password to bcrypt
     *
     * @param String $password
     * @return String $password
     */
    public function encrypt($password)
    {
        return bcrypt($password);
    }

    /**
     * Return randum generated OTP.
     * @return String $otp
     */
    public function generateOTP()
    {
        return "1234";
    
    }

    /**
     * Create api token for User login.
     *   
     * @param User $user
     * @return String $token
     */
    public function createToken(User $user)
    {
        return $user->createToken("login_token")->plainTextToken;
    }
}