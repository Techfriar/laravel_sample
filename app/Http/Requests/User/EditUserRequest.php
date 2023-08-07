<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class EditUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

            return [
                'user_id'=> 'required|exists:users,id',
                'name' => 'string ',
                'phone' => 'numeric|unique:users,phone,'.$this->user_id,
                'image_name' => 'nullable|mimes:png,jpeg,jpg|max:8192',
                'email' =>  'email|unique:users,email,'.$this->user_id,
                'password' => [
                    'nullable',
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised(),
                    'confirmed'
                ],
                'password_confirmation' => 'nullable|min:8',
                'is_admin' => 'numeric',
            ];

    }
    
}