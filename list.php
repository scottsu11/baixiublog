<?php 
  require_once "config.php";
  require_once "functions.php";
  $categoryId = $_GET["categoryId"];
  // $conn = mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_NAME);
  // $sql = "SELECT p.title,p.feature,p.created,p.content,p.views,p.likes,u.nickname,c.`name`,
  // (SELECT count(id) from comments WHERE comments.post_id = p.id )as commentsCount FROM posts p
  // LEFT JOIN users u ON u.id = p.user_id
  // LEFT JOIN categories c ON c.id = p.category_id
  // WHERE p.category_id = {$categoryId}
  // LIMIT 10";
  // $postResult = mysqli_query($conn,$sql);
  // $postArr=[];
  // while ($row = mysqli_fetch_assoc($postResult)) {
  //   $postArr[]=$row;
  // }
  // print_r($postArr)

  // 以下是使用封装好的函数
  $conn = connect();
  $sql = "SELECT p.id,p.title,p.feature,p.created,p.content,p.views,p.likes,u.nickname,c.`name`,
  (SELECT count(id) from comments WHERE comments.post_id = p.id )as commentsCount FROM posts p
  LEFT JOIN users u ON u.id = p.user_id
  LEFT JOIN categories c ON c.id = p.category_id
  WHERE p.category_id = {$categoryId}
  LIMIT 10";
  $postArr = query($conn,$sql);
  // print_r($postArr)
 ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>阿里百秀-发现生活，发现美!</title>
  <link rel="stylesheet" href="static/assets/css/style.css">
  <link rel="stylesheet" href="static/assets/vendors/font-awesome/css/font-awesome.css">
</head>
<body>
  <div class="wrapper">
    <div class="topnav">
      <ul>
        <li><a href="javascript:;"><i class="fa fa-glass"></i>奇趣事</a></li>
        <li><a href="javascript:;"><i class="fa fa-phone"></i>潮科技</a></li>
        <li><a href="javascript:;"><i class="fa fa-fire"></i>会生活</a></li>
        <li><a href="javascript:;"><i class="fa fa-gift"></i>美奇迹</a></li>
      </ul>
    </div>
    <?php 
      include_once "public/_header.php";
    ?>
    <?php
      include_once "public/_aside.php";
    ?>
    <div class="content">
      <div class="panel new">
        <h3><?=$postArr[0]["name"]?></h3>
       <!-- 这里要动态生成结构 -->
       <?php foreach ($postArr as $value): ?>
         <div class="entry">
          <div class="head">
            <a href="detail.php?postId=<?=$value["id"]?>"><?=$value["title"]?></a>
          </div>
          <div class="main">
            <p class="info"><?=$value["nickname"]?> 发表于 <?=$value["created"]?></p>
            <p class="brief"><?=$value["content"]?></p>
            <p class="extra">
              <span class="reading">阅读(<?=$value["views"]?>)</span>
              <span class="comment">评论(<?=$value["commentsCount"]?>)</span>
              <a href="javascript:;" class="like">
                <i class="fa fa-thumbs-up"></i>
                <span>赞(<?=$value["likes"]?>)</span>
              </a>
              <a href="javascript:;" class="tags">
                分类：<span><?=$value["name"]?></span>
              </a>
            </p>
            <a href="javascript:;" class="thumb">
              <img src="<?=$value["feature"]?>" alt="">
            </a>
          </div>
        </div>
       <?php endforeach ?>

       <div class="loadmore">
         <span class="btn">加载更多</span>
       </div>

      </div>
    </div>
    <div class="footer">
      <p>© 2016 XIU主题演示 本站主题由 themebetter 提供</p>
    </div>
  </div>
  <script src="static/assets/vendors/jquery/jquery.js"></script>
  <script type="text/javascript">
    $(function(){
      var categoryId= location.search.split("=")[1];
      var currentPage = 1;
      $(".loadmore > .btn").on("click",function(){
        currentPage++;
        // 请求后台，加载更多与当前分类相关的数据
        $.ajax({
          type:"post",
          url:"api/_getMorePost.php",
          data:{
            "categoryId":categoryId,
            "currentPage":currentPage,
            "pageSize":10,
          },
          success: function(result) {
            console.log(result);
            if(result.code === 1){
              var data = result.data;
              var str="";
              $.each(data, function(index, val) {
               str += `<div class="entry">
               <div class="head">
               <a href="detail.php?postId=${val.id}">${val.title}</a>
               </div>
               <div class="main">
               <p class="info">${val.nickname}发表于 ${val.created}</p>
               <p class="brief">${val.content}</p>
               <p class="extra">
               <span class="reading">阅读(${val.views})</span>
               <span class="comment">评论(${val.commentsCount})</span>
               <a href="javascript:;" class="like">
               <i class="fa fa-thumbs-up"></i>
               <span>赞(${val.likes})</span>
               </a>
               <a href="javascript:;" class="tags">
               分类：<span>${val.name}</span>
               </a>
               </p>
               <a href="javascript:;" class="thumb">
               <img src="${val.feature}" alt="">
               </a>
               </div>
               </div>`;
              });
              $(str).insertBefore(".loadmore");
              var maxPage = Math.ceil(result.postCount/10);
              if(currentPage === maxPage) {
                $(".loadmore > .btn").hide();
              }
            }
          }
        })
      })
    })
  </script>
</body>
</html>