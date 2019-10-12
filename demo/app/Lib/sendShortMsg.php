<?php


namespace App\Lib;

use App\Lib\Ucpaas;

class sendShortMsg
{
    protected const APP_ID = "98164f1b36d046d4a0f39e1d955e846a";//应用的ID，可在开发者控制台内的短信产品下查看
    protected const TEMPLATE_ID = "481132";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
    protected const UID = "2d92c6132139467b989d087c84a365d8";
    protected $ucpass;

    public function __construct()
    {
        $options['accountsid'] = '1201dffa333360cbc6f43bdad8969618';
        //填写在开发者控制台首页上的Auth Token
        $options['token'] = 'b77f44ae65fc4ba0901b32e08f6519d5';
        $this->ucpass = new Ucpaas($options);
    }

    public function  send($captcha , $phone)
    {
        $res = $this->ucpass->SendSms(self::APP_ID, self::TEMPLATE_ID, $captcha, $phone, self::UID);
        return $res;
    }
}
