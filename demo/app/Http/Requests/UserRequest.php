<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        switch ($this->getPathInfo()) {
            case  '/api/sign-up' :
                return [
                    'name' => 'required|max:16',
                    'email' => 'required|unique:users,email',
                    'password' => 'required|between:6,20',
                    'phone' => 'required|unique:users,phone',
                ];
            case '/api/login' :
                return [
                    'name' => 'required|max:16|exists:users,name',
                    'password' => 'required|between:6,20'
                ];
            case '/api/reset-password':
                return [
                    'old_password' => ['required', 'between:6,20'],
                    'new_password' => ['required', 'between:6,20'],
                ];
            case '/api/find-password':
                return [
                    'user' => 'required|between:3,20',
                    'email' => 'required|exists:users,email'
                ];
        }

    }

}
