@component('mail::message')
# 亲爱的用户

重置密码的验证码为： {{$user['captcha']}} 验证码有效时间为 60秒

@component('mail::button', ['url' => $user['returnUrl']])
    激活
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
