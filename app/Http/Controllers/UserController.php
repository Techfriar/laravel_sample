<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\EditUserRequest;
use App\Http\Requests\User\GetUserRequest;
use App\Http\Requests\User\ListUserRequest;
use App\Http\Requests\User\UserViewRequest;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepositoryInterface as UserRepo;
use App\Support\Facades\AuthSupport;
use App\Support\Facades\CommonSupport;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *      path="/create_user",
     *      operationId="createUser",
     *      tags={"User"},
     *      summary="Create user",
     *      security={
     *         {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          required=true,
     *          name="name",
     *          in="query",
     *          description="Enter the name",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  @OA\Parameter(
     *          required=false,
     *          name="phone",
     *          in="query",
     *          description="Enter phone number",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          required=true,
     *          name="email",
     *          in="query",
     *          description="Enter Email address",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          required=true,
     *          name="password",
     *          in="query",
     *          description="Enter password",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          required=true,
     *          name="password_confirmation",
     *          in="query",
     *          description="Reenter password",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          required=true,
     *          name="is_admin",
     *          in="query",
     *          description="Is this user an admin(Yes=>1. No=>0)",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             description="Upload file",
     *                             property="image_name",
     *                             type="string", format="binary"
     *                         )
     *                     )
     *                 }
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *   )
     * )
     *
     *   
     * To Create an User
     */
    public function createUser(CreateUserRequest $request, UserRepo $userRepo)
    {
        $userData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_admin' => $request->is_admin,
            'status' => "1",
            'password' => AuthSupport::encrypt($request->password),
        ];

        if (($request->file('image_name'))) {
            $file = $request->file('image_name');
            $userData['image_name'] = $file->getClientOriginalName();
            $userData['image_path'] = CommonSupport::uploadPublicFile($file, 'photos/profile');
        }
        
        $saveUser = $userRepo->saveUser($userData);
        if(!empty($saveUser)){
            $data = new UserResource($saveUser);
            $response = ['status' => true,  'message' => 'User Added Successfully', 'data' => $data];
            return response()->json($response, 200);
        }
        $response = ['status' => false, 'message' => 'Unable to Add User', 'data' => []];
        return response()->json($response, 200);
    }

    /**
     * @OA\Post(
     *      path="/get_user",
     *      operationId="getUser",
     *      tags={"User"},
     *      summary="Get User details",
     *      security={
     *         {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          required=true,
     *          name="user_id",
     *          in="query",
     *          description="Enter User id",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *   )
     * )
     *
     *   
     * To get an user
     */
    public function getUserById(GetUserRequest $request, UserRepo $userRepo)
    {
        $user = $userRepo->getUser($request->user_id);
        if(!empty($user)){
            $data = new UserResource($user);
            $response = ['status' => true,  'message' => 'User details', 'data' => $data];
            return response()->json($response, 200);
        }
        $response = ['status' => false, 'message' => 'Unable to fetch user details', 'data' => []];
        return response()->json($response, 200);
    }

    /**
     * @OA\Get(
     *      path="/user_list",
     *      operationId="listUser",
     *      tags={"User"},
     *      summary="list users",
     *      security={
     *         {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *   )
     * )
     *
     *   
     * To list users
     */
    public function listUsers(ListUserRequest $request, UserRepo $userRepo)
    {
        $listUsers = $userRepo->getAllUsers();
        if (!empty($listUsers)){
            $data = UserResource::collection($listUsers);
            $response = ['status' => true,  'message' => 'User details', 'data' => $data];
            return response()->json($response, 200);
        }
        $response = ['status' => false, 'message' => 'Unable to list users', 'data' => []];
        return response()->json($response, 200);
    }

    /**
     * @OA\Post(
     *      path="/edit_user",
     *      operationId="editUser",
     *      tags={"User"},
     *      summary="edit user",
     *      security={
     *         {"bearerAuth": {}}
     *      },
     *  @OA\Parameter(
     *          required=true,
     *          name="user_id",
     *          in="query",
     *          description="Enter user id",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="Enter name",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ), 
     *  @OA\Parameter(
     *          name="phone",
     *          in="query",
     *          description="Enter phone number",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="Enter Email address",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="Enter password",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password_confirmation",
     *          in="query",
     *          description="Reenter password",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * @OA\Parameter(
     *          name="is_admin",
     *          in="query",
     *          description="Is this user an admin(Yes=>1. No=>0)",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             description="Upload file",
     *                             property="image_name",
     *                             type="string", format="binary"
     *                         )
     *                     )
     *                 }
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *   )
     * )
     *
     *   
     * To edit an user.
     */
    public function editUser(EditUserRequest $request, UserRepo $userRepo)
    {
        $user = $userRepo->getUser($request->user_id);

        if (!empty($user)) {
            $userData = [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => $request->password ? AuthSupport::encrypt($request->password):null,
                'is_admin' => $request->is_admin,
            ];

            if (($request->file('image_name'))) {
                $file = $request->file('image_name');
                $userData['image_name'] = $file->getClientOriginalName();
                $userData['image_path'] = CommonSupport::uploadPublicFile($file, 'photos/profile');
            }

            $userEdited = $userRepo->editUser($request->user_id, $userData);
            if(!empty($userEdited)){
                $data = new UserResource($userEdited);
                $response = ['status' => true,  'message' => 'User updated successfully', 'data' => $data];
                return response()->json($response, 200);
            }
            $response = ['status' => false, 'message' => 'Unable to update users', 'data' => []];
            return response()->json($response, 200);
        }
    }

    /**
     * @OA\Post(
     *      path="/delete_user",
     *      operationId="deleteUser",
     *      tags={"User"},
     *      summary="delete user",
     *      security={
     *         {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          required=true,
     *          name="user_id",
     *          in="query",
     *          description="Enter user id",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *   )
     * )
     *
     *   
     * To delete an user
     */
    public function deleteUser(DeleteUserRequest $request, UserRepo $userRepo)
    {
        $user = $userRepo->getUser($request->user_id);
        if (!empty($user)) {
            $userDelete = $userRepo->deleteUser($request->user_id);
            if(!empty($userDelete)){
                $response = ['status' => true,  'message' => 'User deleted successfully'];
                return response()->json($response, 200);
            }
            $response = ['status' => false, 'message' => 'Unable to update users', 'data' => []];
            return response()->json($response, 200);
        }
        $response = ['status' => false, 'message' => 'User is not found.', 'data' => []];
        return response()->json($response, 200);
    }

    /**
     * @OA\Post(
     *      path="/my_profile",
     *      operationId="myProfile",
     *      security={
     *         {"bearerAuth": {}}
     *      },
     *      tags={"User"},
     *      summary="Get My Profile",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *  )
     *
     *
     * Retrieving All The Profile Details
     *
     * @param UserViewRequest $request, UserRepo $userRepo
     * @return void
     */
    public function myProfile(UserViewRequest $request, UserRepo $userRepo)
    {
        $id = auth()->user();
        $user = $userRepo->getUser($id->id);
        if (!empty($user)) {
            $data = new UserResource($user);
            $response = ['status' => true,  'message' => 'My Profile :', 'data' => $data];
            return response()->json($response, 200);
        }
        $response = ['status' => false, 'message' => 'Unable to Display Profile Details', 'data' => []];
        return response()->json($response, 200);
    }
}