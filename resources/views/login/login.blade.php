<!DOCTYPE html>
<html lang="en">

<head>
    <script src="/assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
</head>

<body class="login-page sidebar-collapse">
<p style="color: chartreuse">
    @if(!empty($errors->first() ))
        {{$errors->first()}}
    @endif
</p>
    <form>
    @csrf
        <div class="content">
            <div class="input-group form-group-no-border input-lg">
             用户名：   <input type="text" class="form-control"  name="name">
            </div>
            <div class="input-group form-group-no-border input-lg">
             密码：   <input type="password"  class="form-control" name="pwd">
            </div>
        </div>
        <div class="footer text-center">
            <input type="button" class="btn btn-primary btn-round btn-lg btn-block" value="登陆">

        </div>
    </form>
</body>
<!--   Core JS Files   -->
</html>
<script>
        $(document).on('click','.btn',function(){
            // alert(8520);return;
            var name = $("[name=name]").val();
            var pwd = $("[name=pwd]").val();
            // alert(name);
            // alert(pwd);
            $.ajax({
            url:"http://1904.com/login/do_login",
            dataType:"json",
            data:{name:name,pwd:pwd},
            type:"post",
            success:function(res){
                    // console.log(res);
                    if(res.code==200)
                    {
                        alert(res.msg);
                        location.href='/admin/index';
                    }else{
                        alert(res.msg);
                        location.href='/login/login';
                    }
                }
            })
        })
</script>
