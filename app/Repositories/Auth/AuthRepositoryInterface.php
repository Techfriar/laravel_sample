<?php

namespace App\Repositories\Auth;

use App\Models\User;

interface AuthRepositoryInterface
{


/**
   * The OTP stored against key in redis .
   *
   * @param String $key
   * @param String $otp
   * @param DateTime $expiry
   * @return String $otp | false
   */
  public function storeOTP($key, $otp, $expiry);


  /**
   * Retrive the OTP against the key which is stored in Redis.
   *
   * @param String $key
   * @return String $otp | false
   */
  public function retrieveOTP($key);

    /**
     * The Email stored against token in redis .
     *
     * @param String $token
     * @param String $email
     * @param DateTime $expiry
     * @return String $token | false
     */
    public function storeOtpTokenData($token, $email);

    /**
     * Retrive the Email stored against token which is stored in Redis
     *
     * @param String $token
     * @return String $email | false [ Email ]
     */
    public function retrieveOtpTokenData($token);

    /**
     * Reset user password.     
     * @param String $password
     * @param User $user
     * @return User $user
     */
    public function resetPassword($password, $user);
}