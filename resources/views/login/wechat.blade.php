<!doctype html>
<html lang="en">
<head>
    <script src="/assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div class="content">
    <div class="input-group form-group-no-border input-lg">
        <img src="{{$data}}" alt="" width="100">
    </div>
</body>
</html>
<script>
    //js时间设置
    var t = setInterval("check();",2000);
    //获取二维码表示
    var status = "{{$status}}";
    //定时访问登陆
    function check()
    {
        $.ajax({
            url:"{{url('/login/demowechat')}}",
            dataType:"JSON",
            data:{status:status},
            success:function (res) {
              if (res.ret == 1)
              {
                  clearInterval(t);
                  alert(res.msg);
                  location.href = "{{url('/admin/index')}}";
              }
            }
        })
    }
</script>
