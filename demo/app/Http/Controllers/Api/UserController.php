<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class UserController extends BaseController
{
    use AuthenticatesUsers;

    public function signUp(UserRequest $request)
    {
        $data = $request->only(['name', 'email', 'password', 'phone']);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        if ($user) {
            return $this->response()->item($user, new UserTransformer());
        } else {
            return $this->response()->array(['status_code' => '403', 'msg' => '注册失败']);
        }
    }

    public function login(UserRequest $request)
    {
        $data = ['name' => $request->name, 'password' => $request->password];
        $userInfo = User::whereName($data['name'])->first();
        if ($userInfo && Hash::check($data['password'], $userInfo->password)) {
            $token = JWTAuth::fromuser($userInfo);
            $this->clearLoginAttempts($request);//清除登录次数
            return $this->response()->array(['status_code' => '200', 'msg' => '登录成功', 'token' => $token]);
        } else {
            return $this->response()->array(['status_code' => '403', 'msg' => '用户密码不正确']);
        }
    }

    public function userInfo()
    {
        $user = $this->auth()->user();
        return $this->response->item($user, new UserTransformer());
    }

    public function resetPassword(UserRequest $request)
    {
        $old_pwd = $request->old_password;
        $new_pwd = $request->new_password;
        $userInfo = $this->auth()->user();
        if(!Hash::check($old_pwd , $userInfo['password'])){
            return $this->response->array(['status_code' => '200' , 'msg' => '密码错误,请重新输入']);
        }
        if($old_pwd == $new_pwd){
            return $this->response->array(['status_code' => '200' , 'msg' => '不能返回最近使用的密码']);
        }
        $userInfo->update(['password' => bcrypt($new_pwd)]);
        return $this->response->array(['status_code' => '200' , 'msg' => '重置密码成功']);
    }

    public function findPwd(UserRequest $request , CommonController $common)
    {

        $email  = $request->email;
        $user = User::whereEmail($email)->first();
        $res = visits($user);
        dd($res);
        $captcha = $common -> generate_captcha();
        $common->sendEmail($captcha , $email);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        $this->guard()->logout();
    }

}
