<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgotPasswordOtpVerifyRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordResetRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepositoryInterface as UserRepo;
use App\Support\Facades\AuthSupport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Auth\AuthRepositoryInterface as AuthRepo;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    

    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="login",
     *      tags={"Auth"},
     *      summary="Login using registered email and password.",
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="Enter the email.",
     *          @OA\Schema(
     *              type="string"
     *        )
     *      ),
     * 
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="Enter the password",
     *          @OA\Schema(
     *              type="string",
     *        )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *   )
     * )
     *
     * Login using email and password
     * 
     */
    public function logIn(LoginRequest $request, UserRepo $userRepo)
    {
        $user = $userRepo->getUserByEmail($request->email);
        if (!empty($user)){
            if (Hash::check($request->password, $user->password)){
                $token = AuthSupport::createToken($user);
                Auth::login($user);
                $data['user'] = new UserResource($user);
                $data['token'] = $token;
                $response = ['status' => true, 'message' => 'Loggedin successfully.', 'data' => $data];
                return response()->json($response, 200);
            }else {
                $response = ['status' => false, 'message' => 'Provided password does not match our Records. Try Again!', 'data' => []];
                return response()->json($response, 200);
            }
        }else {
            $response = ['status' => false, 'message' => 'Email is not found.', 'data' => []];
            return response()->json($response, 200);
        }
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="logout",
     *      tags={"Auth"},
     *      summary="Logout Current Logged In User.",
     *      security={
     *         {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *   )
     * )
     *
     *
     * Logout Current Logged In User
     *
     * @return void
     */
    public function logOut(Request $request)
    {
        $user = auth()->user();
        
        // Revoke the current access token
        $user->currentAccessToken()->delete();

        $response = ['status' => true, 'message' => 'Logout Successfully', 'data' => []];
        return response()->json($response, 200);
    }

    /**
     * @OA\Post(
     *      path="/forgot_password",
     *      operationId="forgotPassword",
     *      tags={"Auth"},
     *      summary="Forgot Password",
     *      security={
     *         {"bearerAuth": {}}
     *      },
     *  
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="Enter the email ",
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
     * @param ForgotPasswordRequest $request
     * 
     * @return array
     */
    public function forgotPassword(ForgotPasswordRequest $request, AuthRepo $authRepo, UserRepo $userRepo)
    {
        $user = $userRepo->getUserByEmail($request->email);
        if (!empty($user)) {
            $otpToSend = AuthSupport::generateOTP();
            $otp = $authRepo->storeOTP($request->email, $otpToSend, now()->addMinute(10));
            $mailData = [
                'name' => $user->name,
                'title' => 'Please find the OTP to reset your password',
                'otp' => $otp
            ];
            
            $mailSend = Mail::to($user->email)->send(new ForgotPassword($mailData));
            if ($mailSend) {
                $response = ['status' => true, 'message' => 'OTP Sent To Your Mail Successfully.', 'data' => []];
                return response()->json($response, 200);
            }
            }else {
            $response = ['status' => false, 'message' => 'Email is not found.', 'data' => []];
            return response()->json($response, 200);
        }
    }

        /**
     * @OA\Post(
     *      path="/forgot_password_verify_otp",
     *      operationId="forgotPasswordVerifyOTP",
     *      tags={"Auth"},
     *      summary="Verify OTP for forgot password ",
     *      security={
     *         {"bearerAuth": {}}
     *      },
     *       @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="Enter the email",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="otp",
     *          in="query",
     *          description="Enter the OTP",
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
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *   )
     * )
     *
     * To Send Email OTP
     * 
     *
     * @param ForgotPasswordOtpVerifyRequest $request
     * 
     * @return array
     */
    public function forgotPasswordVerifyOTP(ForgotPasswordOtpVerifyRequest $request, AuthRepo $authRepo, UserRepo $userRepo)
    {
        $otp = $authRepo->retrieveOTP($request->email);
        if ($otp == $request->otp) {
            $token = Str::random(9);
            $token = $authRepo->storeOtpTokenData($token, $request->email);
            $response = ['status' => true, 'message' => 'OTP Verification Success', 'data' => ['token' => $token]];
            return response()->json($response, 200);
        }
        $response = ['status' => false, 'message' => 'OTP verification failed, Invalid OTP provided.', 'data' => ['token' => ""]];
        return response()->json($response, 200);
    }

    /**
     * @OA\Post(
     *      path="/forgot_password_reset",
     *      operationId="forgotPasswordReset",
     *      tags={"Auth"},
     *      summary="Reset Forgot Password",
     *      security={
     *         {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="token",
     *          in="query",
     *          description="Enter the token for password reset",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="Enter the password",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ), 
     *      @OA\Parameter(
     *          name="password_confirmation",
     *          in="query",
     *          description="Confirm the password",
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
     * Password Reset 
     * @param ForgotPasswordResetRequest $request
     * 
     * @return array
     */
    public function forgotPasswordReset(ForgotPasswordResetRequest $request, AuthRepo $authRepo,UserRepo $userRepo)
    {
        $email = $authRepo->retrieveOtpTokenData($request->token);
        $user = $userRepo->getUserByEmail($email);
        if (!empty($user)) {
            $authRepo->resetPassword(AuthSupport::encrypt($request->password), $user);
            $response = ['status' => true, 'message' => 'Password Reset Successfully', 'data' => []];
            return response()->json($response, 200);
        } else {
            $response = ['status' => false, 'message' => 'Email is not found.', 'data' => []];
            return response()->json($response, 200);
        }
    }

    /**
     * Download Public Photos
     * 
     * @param Request $request
     * @return array
     */

     public function downloadPublicPhoto(Request $request)
     {
         if (Storage::disk('public')->exists($request->name)) {
             return Storage::disk('public')->download($request->name);
         } else {
             return null;
         }
     }
}
