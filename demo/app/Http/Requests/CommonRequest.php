<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommonRequest extends FormRequest
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
        if($this->getPathInfo() == '/api/send-captcha'){
            return [
                'phone' => ['regex:/^1[34578]\d{9}$/ims','exists:users,phone'],
                'email' => ['regex:/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims','exists:users,email']
            ];
        }elseif ($this->getPathInfo() == '/api/check-captcha'){
            return [
                'captcha' => 'required|digits:4'
            ];
        }


    }
}
