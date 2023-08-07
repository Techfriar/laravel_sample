<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Redis;

class AuthRepository implements AuthRepositoryInterface
{
    
    /**
     * The OTP stored against key in redis .
     *
     * @param String $key
     * @param String $otp
     * @param DateTime $expiry
     * @return String $otp | false
     */
    public function storeOTP($key, $otp, $expiry)
    {
        $redis = Redis::connection();
        $redis->set($key . '_otp', $otp);
        $redis->set($key . '_expiry', $expiry);
        return $otp;
    }

    /**
     * Retrive the OTP against the key. which is stored in Redis.
     *
     * @param String $key
     * @return String $otp | false
     */
    public function retrieveOTP($key)
    {
        $redis    = Redis::connection();
        $response = $redis->get($key . '_otp');
        return $response;
    }

    /**
     * The Email stored against token in redis .
     *
     * @param String $token
     * @param String $email
     * @param DateTime $expiry
     * @return String $token | false
     */
    public function storeOtpTokenData($token, $email)
    {
        $redis = Redis::connection();
        $redis->set($token . '_token', $email);
        return $token;
    }

    /**
     * Retrive the Email stored against token which is stored in Redis
     *
     * @param String $token
     * @return String $email | false [ Email ]
     */
    public function retrieveOtpTokenData($token)
    {
        $redis    = Redis::connection();
        $response = $redis->get($token . '_token');
        return $response;
    }

    /**
     * Reset user password.     
     * @param String $password
     * @param User $user
     * @return User $user
     */
    public function resetPassword($password, $user)
    {
        $user->password = $password;
        $user->save();
        return $user;
    }
}