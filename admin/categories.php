<?php 
require_once "../config.php";
require_once "../functions.php";
checkLogin();
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
  <link rel="stylesheet" href="../static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../static/assets/css/admin.css">
  <script src="../static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include_once "public/_navbar.php" ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display: none;">
        <strong>错误！</strong><span id="msg">发生XXX错误</span>
      </div>
      <div class="row">
        <div class="col-md-4">
          <form id="data">
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <label for="classname">类名</label>
              <input id="classname" class="form-control" name="classname" type="text" placeholder="类名">
              <p class="help-block"></p>
            </div>
            <div class="form-group">
              <input type="button" value="添加" class="btn btn-primary" id="btn-add">
              <input type="button" value="编辑完成" class="btn btn-primary" id="btn-edit" style="display: none;">
              <input type="button" value="取消编辑" class="btn btn-primary" id="btn-cancel" style="display: none;">
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action" style="height:30px;">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none" id="delMore">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th>类名</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <!-- 这里要动态生成结构 -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php include_once "public/_aside.php" ?>

  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script src="../static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script type="text/javascript">
    $(function(){
      $.ajax({
        type: "POST",
        url: "api/_getCategoryData.php",
        success: function(result){
          // console.log(resul);
          var str="";
          if(result.code ==1){
            $.each(result.data,function(index,val){
              str += `<tr data-categoryid="${val.id}">
              <td class="text-center"><input type="checkbox"></td>
              <td>${val.name}</td>
              <td>${val.slug}</td>
              <td>${val.classname}</td>
              <td class="text-center">
              <a href="javascript:;" class="btn btn-info btn-xs edit" data-categoryid="${val.id}">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs delete">删除</a>
              </td>
              </tr>`;  
            });
            $("tbody").html($(str));
          }
        }
      });

      // 为“添加”按钮注册事件：点击添加按钮，在页面添加新的分类
      $("#btn-add").on("click",function(){
        var name = $("#name").val();
        var slug = $("#slug").val();
        var classname = $("#classname").val();
        // 判断添加的数据是否为空
        if(name == ""){
          $("#msg").text("分类的名称不能为空");
          $(".alert").show();
          return;
        }
        if(slug == ""){
          $("#msg").text("分类的别名不能为空");
          $(".alert").show();
          return;
        }
        if(classname == ""){
          $("#msg").text("分类的图标样式不能为空");
          $(".alert").show();
          return;
        }
        $.ajax({
          type: "POST",
          url: "api/_addCategory.php",
          data: $("#data").serialize(),
          success: function(result){
            // console.log(result);  
            var str="";
            if(result.code ==1){
              str += `<tr>
              <td class="text-center"><input type="checkbox"></td>
              <td>${name}</td>
              <td>${slug}</td>
              <td>${classname}</td>
              <td class="text-center">
              <a href="javascript:;" class="btn btn-info btn-xs edit">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs delete">删除</a>
              </td>
              </tr>`;  
              $(str).appendTo("tbody");
            }
          }
        })
      });

      // 声明一个全局变量来记录当前编辑的是第几行
      var currentRow = null;

      // 为“编辑”按钮注册点击事件
      $("tbody").on("click",".edit",function(){
        var name = $(this).parents("tr").children().eq(1).text();
        var slug = $(this).parents("tr").children().eq(2).text();
        var classname = $(this).parents("tr").children().eq(3).text();
        var categoryId = $(this).attr("data-categoryid");
        currentRow = $(this).parents("tr");
        $("#name").val(name);
        $("#slug").val(slug);
        $("#classname").val(classname);
        $("#btn-add").hide();
        $("#btn-edit").show();
        $("#btn-cancel").show();
        // 将当前编辑的分类的id保存为“编辑完成”按钮的自定义属性的值
        $("#btn-edit").attr("data-categoryid",categoryId);
      });
      
      // 为“编辑完成”按钮注册点击事件
      $("#btn-edit").on("click",function(){
        var name = $("#name").val();
        var slug = $("#slug").val();
        var classname = $("#classname").val();
        var categoryId = $(this).attr("data-categoryid");
        if(name == ""){
          $("#msg").text("分类的名称不能为空");
          $(".alert").show();
          return;
        }
        if(slug == ""){
          $("#msg").text("分类的别名不能为空");
          $(".alert").show();
          return;
        }
        if(classname == ""){
          $("#msg").text("分类的图标样式不能为空");
          $(".alert").show();
          return;
        }
        $.ajax({
          type: "POST",
          url: "api/_updateCategory.php",
          data:{
            id:categoryId,
            name:name,
            slug: slug,
            classname:classname
          },
          success: function(result){
          // console.log(result);
            if(result.code ==1){
              var name = $("#name").val();
              var slug = $("#slug").val();
              var classname = $("#classname").val();

              $("#btn-add").show();
              $("#btn-edit").hide();
              $("#btn-cancel").hide();
              currentRow.children().eq(1).text(name);
              currentRow.children().eq(2).text(slug);
              currentRow.children().eq(3).text(classname);
              $("#name").val("");
              $("#slug").val("");
              $("#classname").val("");
            }
          }
        })
      });

      // 为“取消编辑”按钮注册点击事件
      $("#btn-cancel").on("click",function(){
        $("#btn-add").show();
        $("#btn-edit").hide();
        $("#btn-cancel").hide();
        $("#name").val("");
        $("#slug").val("");
        $("#classname").val("");
      });

      // 为“删除”按钮注册点击事件
      $("tbody").on("click",".delete",function(){
        var row = $(this).parents("tr");
        var categoryId =row.attr("data-categoryid");
        $.ajax({ 
          type: "post",
          url: "api/_deleteCategory.php",
          data:{
            id:categoryId
          },
          success:function(result){
            if(result.code==1){
              row.remove();
            }
          }
        })
      })

      // 为“全选”按钮注册事件
      $("thead input").on("click",function() {
        // 获取当前复选框是否选中
        var status = $(this).prop("checked");
        $("tbody input").prop("checked",status);
        if(status){
          $("#delMore").show();
        }else {
          $("#delMore").hide();
        }
      })

      // 使用事件委托为tbody下的多选框注册点击事件
      $("tbody").on("click","input",function(){
        var all = $("thead input");
        var tb_inputs = $("tbody input");
        // if(cks.size()== $("tbody input:checked").size()){
        //   all.prop("checked",true);
        // }else {
        //   all.prop("checked",false);
        // }
        // 上边这段代码可以简写为：
        all.prop("checked",$("tbody input:checked").size() == tb_inputs.size());

        if($("tbody input:checked").size() >= 2){
          $("#delMore").show();
        }else {
          $("#delMore").hide();
        }
      })

      // 为“批量删除”注册点击事件，实现相应的功能
      $("#delMore").on("click",function(){
        var ids =[];
        var cks = $("tbody input:checked");
        cks.each(function(index,ele){
          ids.push($(ele).parents("tr").attr("data-categoryid"));
        }) 
        $.ajax({
          type: "post",
          url: "api/_deleteMoreCategories.php",
          data:{
            ids:ids
          },
          success:function(result){
            if(result.code ==1){
              cks.parents("tr").remove();
            }
          }
        })
      })
    })
  </script>
</body>
</html>
