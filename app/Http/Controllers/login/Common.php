<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Common extends Controller
{
    const appid = 'wxc748bb8c67bacadb';
    const appsecret = '7bf96fe7dfe2cd8374a3b69c29c6f189';

    public static function getToken()
    {
        //缓存里有数据 直接读取
        $access_token=Cache::get("access_token");
        if(empty($access_token))
        {
            //缓存中没有数据 调用接口获取

            $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".Self::appid."&secret=".Self::appsecret;
            $data=file_get_contents($url);
            $data=json_decode($data,true);
            $access_token=$data['access_token'];
            //存储2小时
            Cache::put("access_token",$access_token,7200);
        }
        return $access_token;
    }

    public static function Post($url,$postData)
    {
        //初始化： curl_init
        $ch = curl_init();
        //设置	curl_setopt
        curl_setopt($ch, CURLOPT_URL, $url);  //请求地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //返回数据格式
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        //访问https网站 关闭ssl验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        //执行  curl_exec
        $result = curl_exec($ch);
        //关闭（释放）  curl_close
        curl_close($ch);
        return $result;
    }
}
