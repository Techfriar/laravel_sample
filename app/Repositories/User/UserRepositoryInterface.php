<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
   /**
   * Retrive user data by email.
   *
   * @param String $email
   * @return User $user|false
   */
  public function getUserByEmail($email);

    /**
     * Save an user
     *
     * @param array $details
     * @return User $user|false
     */
    public function saveUser($details);

    /**
     * Get a User By Id
     *
     * @param array $userId
     * @return User $user|false
     */
    public function getUser($userId);

    /**
     * list Users
     *
     * @return User $listUser
     */
    public function getAllUsers();

    /**
     * To edit an user
     *
     * @param array $userId
     * @param array $userDetails
     * @return User $user
     */
    public function editUser($id, $data);

    /**
     * To delete an user
     *
     * @param array $userId
     * @return boolean
     */
    public function deleteUser($userId);
}