<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Session;

class LoginController extends Common
{
    //
    public function login(Request $request)
    {
        return view('login/login');
    }

    public function do_login(Request $request)
    {
        //判断用户名密码
        $all = $request->all();
        // dd($all);
        if($all['name']=="" || $all['pwd']=="")
        {
            $arr['code'] = 1;
            $arr['msg'] = '参数错误，用户名或者密码不能为空';
            $arr['data'] = [];
            return json_encode($arr);
        }
        //判断用户是否存在
        $info = UserModel::where('name',$all['name'])->first();
        // dd($info);
        if(!$info)
        {
            $arr['code'] = 1;
            $arr['msg'] = '输入的用户名不存在，请核对';
            $arr['data'] = [];
            return json_encode($arr);
        }
//        $time = time();
        if($info)
        {
            if($info['pwd']==$all['pwd'] && time()>($info['locktime']+600))
            {
                //登陆成功
                $id = $info['u_id'];
                $data['u_id'] = $info['u_id'];
                $data['locknum'] = 3;
                $data['sessionid'] = Session::getid();
                // dd($data);
                $data['sessiontime'] = time()+20;
                // dd($data);
                UserModel::where('u_id',$id)->update($data);
                $request->session()->put('userinfo', $info);
                $arr['code'] = 200;
                $arr['msg'] = '登录成功';
                $arr['data'] = [];
                return json_encode($arr);
            }else{
                //已经被锁定什么是解封
                if($info['locknum']<=1){
                    $info['locknum'] = 0;
                    $data['locknum'] = $info['locknum'];
                    $data['u_id'] = $info['u_id'];
                    $data['locktime'] = time()+600;
                    // dd($data['locktime']);
                    $id = $info['u_id'];
                    UserModel::where('u_id',$id)->update($data);
                    // dd($res);
                    $info = UserModel::where('name',$all['name'])->first();
                    // dd($info);
                    $endtime = $info['locktime'];
                     // dd($starttime);
                    $arr['code'] = 1;
                    $arr['msg'] = "您的账号已被停封，停封至:".date('Y年m月d日 H时i分s秒',$endtime);
                    $arr['data'] = [];
                    return json_encode($arr);
                }else{
                    //错误3次锁定账号
                    $info['locknum'] = $info['locknum']-1;
                    $data['locknum'] = $info['locknum'];
                    $data['u_id'] = $info['u_id'];
                    $id = $info['u_id'];
                    UserModel::where('u_id',$id)->update($data);
                    $arr['code'] = 1;
                    $arr['msg'] = "您的密码有误，还可以输入".$info['locknum']."次";
                    $arr['data'] = [];
                    return json_encode($arr);
                }
            }
        }
    }

    public function wechat()
    {
        //带参数的二维码
        $access_token=$this->getToken();
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$access_token}";
        $postData=[
            'expire_seconds'=> 60,
            'action_name'=>'QR_STR_SCENE',
            'action_info'=> [
                "scene"=> [
                    'scene_str'=>"唯一标识"
                ],
            ],
        ];
        $postData=json_encode($postData,true);
        $res=$this->Post($url,$postData);
        $res=json_decode($res,true);
        $data="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$res['ticket'];
        var_dump($data);die;
        return view('login.wechat');
    }
}
