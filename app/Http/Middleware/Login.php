<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\Models\UserModel;


class Login
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $u_id = session('userinfo')['u_id'];
        $sessionid = Session::getid();
        $info = UserModel::where('u_id',$u_id)->first();
        if($sessionid!=$info['sessionid'])
        {
            return redirect("/login/login")->withErrors(['您的账号异地登陆了。']);
        }
        if(time()>$info['sessiontime'])
        {
            return redirect("/login/login")->withErrors(['长时间位操作请重新登陆。']);
        }
        return $next($request);
    }
}
