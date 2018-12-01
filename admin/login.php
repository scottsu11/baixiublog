<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="../static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../static/assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap">
      <img class="avatar" src="../static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display: none;">
        <strong>错误！</strong> <span id="msg">用户名或密码错误！</span>
      </div>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" type="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" type="password" class="form-control" placeholder="密码">
      </div>
      <span class="btn btn-primary btn-block" id="btn-login">登 录</span>
    </form>
  </div>
  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script>
    $(function(){
      $("#btn-login").on("click",function(){
        var email = $("#email").val();
        var password = $("#password").val();
        var reg = /^\w+@\w+[.]\w+$/;
        if(!reg.test(email)){
          $("#msg").text("您输入的邮箱有误，请重新输入");
          $(".alert").show();
          return;
        }
        $.ajax({
          type:"POST",
          url: "api/_userLogin.php",
          data: {
            "email" :email,
            "password": password
          },
          success: function(result){
            // console.log(result);
            if(result.code ==1){
              location.href = "index.php";
            }else {
               $("#msg").text("邮箱或密码错误，请重新输入");
               $(".alert").show();
            }
          }
        })
      })
    })
  </script>
</body>
</html>
