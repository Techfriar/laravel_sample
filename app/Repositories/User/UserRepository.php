<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface
{
   
    /**
     * Retrive user data by email.
     *
     * @param String $email
     * @return User $user|false
     */
    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * Save an user
     *
     * @param array $details
     * @return User $user|false
     */
    public function saveUser($details)
    {
        $user = new User;
        foreach ($details as $key => $value) {
            $user->$key = $value;
        }
        $user->save();
        return $user;
    }

     /**
     * Get an User By Id
     *
     * @param array $userId
     * @return User $user|false
     */
    public function getUser($userId)
    {
        $user = User::where('id', $userId)->first();
        return $user;
    }

    /**
     * list Users
     *
     * @return User $listUsers
     */
    public function getAllUsers()
    {
        $listUsers = User::all();
        return $listUsers;
    }

    /**
     * To edit an user
     *
     * @param array $userId
     * @param array $userDetails
     * @return User $user
     */
    public function editUser($id, $data)
    {
        $user = User::find($id);
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $user->$key = $value;
            }
        }
        $user->save();
        return $user;
    }

    /**
     * To delete an user
     *
     * @param array $userId
     * @return boolean
     */
    public function deleteUser($userId)
    {
        $userDelete = User::find($userId);
        $userDelete->delete();
        return true;
    }

    public function userWithVote()
    {
        $authId = Auth::id();
        $listUsers = User::with(['voting' => function ($query) use ($authId) {
            $query->where('voter_id', $authId);
        }])->get();
        return $listUsers;
    }
}