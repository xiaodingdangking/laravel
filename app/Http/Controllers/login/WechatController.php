<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Session;

class WechatController extends Common
{
    public function demo(Request $request)
    {
        $echostr=$request->input("echostr");
        if(!empty($echostr)){
            echo $echostr;die;
        }
        $xml = file_get_contents("php://input");
        $xmlobj = simplexml_load_string($xml);
        if ($xmlobj->Event == 'subscribe' && $xmlobj->MsgType == 'event')
        {
            $openid = (string)$xmlobj->FromUserName;
            $EventKey = (string)$xmlobj->EventKey;
            $EventKeys = ltrim($EventKey,'qrscene_');
            if ($EventKeys){
                Cache::put($EventKeys,$openid,20);
                $this -> responseText($xmlobj,'正在扫码中请稍后');
            }
        }

        if ($xmlobj->Event == 'SCAN' && $xmlobj->MsgType == 'event')
        {
            $openid = (string)$xmlobj->FromUserName;
            $EventKey = (string)$xmlobj->EventKey;
            $EventKeys = ltrim($EventKey,'qrscene_');
            if ($EventKeys){
                Cache::put($EventKeys,$openid,20);
                $this -> responseText($xmlobj,'正在扫码中请稍后');
            }
        }
    }

    public function demowechat(Request $request)
    {
        $data = $request->all();
        $openid = Cache::get($data);
//        var_dump($data);die;
        if (!$openid){
            return  json_encode(['ret'=>0,'msg'=>"请先扫码"]);
        }else{
            return  json_encode(['ret'=>1,'msg'=>"登陆成功"]);
        }
    }
    public  function responseText($xmlObj,$msg)
    {
        echo "<xml>
                    <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                    <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                    <CreateTime>".time()."</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[".$msg."]]></Content>
                    </xml>";die;
    }
}
