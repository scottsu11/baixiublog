<?php 
require_once "../config.php";
require_once "../functions.php";
checkLogin();

 ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
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
        <h1>所有文章</h1>
        <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline">
          <select name="" class="form-control input-sm" id="category">
            <option value="all">所有分类</option>
            <!-- <option value="">未分类</option> -->
          </select>
          <select name="" class="form-control input-sm" id="status">
            <option value="all">所有状态</option>
            <option value="drafted">草稿</option>
            <option value="published">已发布</option>
            <option value="trashed">已作废</option>
          </select>
          <!-- <button class="btn btn-default btn-sm">筛选</button> -->
          <input type="button" value="筛选" class="btn btn-default btn-sm" id="btn-sift">
        </form>
        <ul class="pagination pagination-sm pull-right">
          <!-- <li><a href="#">上一页</a></li>
          <li><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">下一页</a></li> -->
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
         
        </tbody>
      </table>
    </div>
  </div>

  <?php include_once "public/_aside.php" ?>

  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script src="../static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script>
    $(function(){
      var statusData = {
        "drafted": "草稿",
        "published": "已发布",
        "trashed":"已作废"
      }
      var currentPage = 1;
      var pageCount = 4;
      var pageSize=10;

       // 封装动态生成分页按钮的函数
      function makePageButton(){ 
        var start = currentPage-2;
        if(start<1){
          start = 1;
        } 
        var end = start + 4;
        if(end>pageCount){
          end=pageCount;
        }
        var html = "";
        if(currentPage!=1){
          html+='<li class="item" data-page="'+(currentPage-1)+'"><a href="javascript:;">上一页</a></li>';
        }

        for(var i=start;i<=end;i++){
          if(i==currentPage){
            html+='<li class="item active" data-page= "'+i+'"><a href="javascript:;">'+i+'</a></li>';
          }else {
            html+='<li class="item" data-page= "'+i+'"><a href="javascript:;">'+i+'</a></li>';
          }  
        }
        if(currentPage != pageCount){
          html+='<li class="item" data-page="'+(currentPage+1)+'"><a href="javascript:;">下一页</a></li>';
        }

        $(".pagination").html(html);
      }
      makePageButton();

      // 封装动态生成表格的函数
      function makeTable(data){
        makePageButton();
        $("tbody").empty();
        $.each(data, function(index, val) {
          var str = `<tr>
          <td class="text-center"><input type="checkbox"></td>
          <td>${val.title}</td>
          <td>${val.nickname}</td>
          <td>${val.name}</td>
          <td class="text-center">${val.created}</td>
          <td class="text-center">${statusData[val.status]}</td>
          <td class="text-center">
          <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
          <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
          </td>
          </tr>`;
          $(str).appendTo('tbody');
        });
      }

      $.ajax({
        type: "POST",
        url: "api/_getPostData.php",
        data:{
          currentPage:currentPage,
          pageSize:pageSize,
          status:$("#status").val(),
          categoryId:$("#category").val()
        },
        success:function(result){
          if(result.code ==1){
            pageCount = result.pageCount;
            var data = result.data;
            makeTable(data);
            
          }
        }
      })
      
      // 为分页按钮注册点击事件
      $(".pagination").on("click",".item",function(){
        currentPage = parseInt($(this).attr("data-page"));
        $.ajax({
          type:"POST",
          url:"api/_getPostData.php",
          data:{
            currentPage:currentPage,
            pageSize:pageSize,
            status:$("#status").val(),
            categoryId:$("#category").val()
          },
          success:function(result){
            if(result.code ==1){
              var data = result.data;
              makeTable(data);
            }            
          }
        })
      })
      
      // 发送异步请求，动态生成“所有分类”下拉列表
      $.ajax({
        type:"POST",
        url:"api/_getCategoryData.php",
        success:function(result){
          // console.log(result);
          if(result.code==1){
            var data = result.data;
            $.each(data, function(index, val) {
              var html = `<option value="${val.id}">${val.name}</option>`;
              $(html).appendTo('#category');
            });  
          }
        }
      })

      // 给“筛选”按钮注册点击事件
      $('#btn-sift').on('click',function(){
        var status = $("#status").val();
        var categoryId = $("#category").val();
        $.ajax({
          type:"POST",
          url:"api/_getPostData.php",
          data: {
            currentPage:currentPage,
            pageSize:pageSize,
            status:status,
            categoryId:categoryId
          },
          success:function(result){
            // console.log(result);
            if(result.code ==1){
              var data = result.data;
              makeTable(data);
            }
          }
        })
      })
    })
  </script>
</body>
</html>
