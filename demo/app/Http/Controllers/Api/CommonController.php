<?php

namespace App\Http\Controllers\Api;

use App\Lib\sendShortMsg;
use App\Http\Requests\CommonRequest;
use App\Mail\SendCaptcha;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Redis;


class CommonController extends BaseController
{
    public function generate_captcha($user=null)
    {
        $captcha = rand(1000, 9999);
        if(!$user){
            Redis::setex('captcha', 60, $captcha);
        }else{
            Redis::setex($user.':captcha', 60, $captcha);
        }
        return $user;
    }

    public function sendCode(CommonRequest $request)
    {
        $user = $this->generate_captcha();

        $captchaType = $request->phone ? 'Phone' : 'Email';
        $res = $this->sendType($captchaType, $request , $user);
        if ($res) {
            return $this->response()->array(['status_code' => '200', 'msg' => '发送验证码成功,请注意查收']);
        } else {
            return $this->response()->array(['status_code' => '403', 'msg' => '发送验证码失败']);
        }
    }

    public function sendType($captchaType, $request , $user)
    {
        $captcha = $user ? Redis::get($user.':captcha') : Redis::get('captcha');
        $flag = false;
        switch ($captchaType) {
            case 'Email':
                $flag = $this->sendEmail($captcha, $request->email);
            case 'Phone':
                $flag = $this->sendPhone($captcha, $request->phone);
        }
        return $flag;
    }

    public function sendEmail($captcha, $email)
    {
        // Mail::send()的返回值为空，所以可以其他方法进行判断
            $user = User::whereEmail($email)->first();
            $user['captcha'] = $captcha;
            $user['returnUrl'] = 'https://www.baidu.com';
            Mail::to($email)->send(new SendCaptcha($user));
            return empty(Mail::failures());

    }

    public function sendPhone($captcha, $phone)
    {
        $sendShort = new sendShortMsg();
        $res = $sendShort->send($captcha, $phone);
        return $res;
    }

    public function checkCaptcha(CommonRequest $request)
    {
        $checkCaptcha = $request->captcha;
        $captcha = Redis::get('captcha');
        if(!$captcha){
            return $this->response->array(['status_code'=>200 , 'msg'=>'验证码已过期,请重新获取']);
        }
        if($captcha != $checkCaptcha){
            return $this->response->array(['status_code'=>200 , 'msg'=>'验证码错误']);
        }
        return $this->response->array(['status_code'=>200 , 'msg'=>'验证通过']);

    }


}
