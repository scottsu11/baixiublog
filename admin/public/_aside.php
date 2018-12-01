<div class="aside">
    <div class="profile">
      <img class="avatar" src="../<?=$_SESSION["avatar"]?>">
      <h3 class="name"><?=$_SESSION["nickname"]?></h3>
    </div>
    <ul class="nav">
      <li class="">
        <a href="index.php"><i class="fa fa-dashboard"></i>仪表盘</a>
      </li>
      <?php 
      // $_SERVER["SCRIPT_FILENAME"]    获取当前执行脚本的绝对路径(文件在磁盘上的路径)
      // basename()  返回路径中的文件名部分
      // explode(分割符，目标字符串)   使用一个字符串分割另一个字符串，返回一个数组
        
        $currentPage = explode(".", basename($_SERVER["SCRIPT_FILENAME"]))[0];
        $pageArr = ["posts","post-add","categories"];
        $bool = in_array($currentPage, $pageArr);

       ?>
      <li>
        <a href="#menu-posts" class="<?php echo $bool ? "" : "collapsed" ?>" data-toggle="collapse" <?php echo $bool ? 'aria-expanded="true"' : "" ?>>
          <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-posts" class="collapse <?php echo $bool ? "in" : "" ?>" <?php echo $bool ? 'aria-expanded="true"' : "" ?>>
          <li><a href="posts.php">所有文章</a></li>
          <li><a href="post-add.php">写文章</a></li>
          <li><a href="categories.php">分类目录</a></li>
        </ul>
      </li>
      <li>
        <a href="comments.php"><i class="fa fa-comments"></i>评论</a>
      </li>
      <li>
        <a href="users.php"><i class="fa fa-users"></i>用户</a>
      </li>
      <li>
        <?php 
          $currentPage = explode(".", basename($_SERVER["SCRIPT_FILENAME"]))[0];
          $pageArr = ["nav-menus","slides","settings"];
          $bool = in_array($currentPage,$pageArr);
         ?>
        <a href="#menu-settings" class="<?php echo $bool ? "" : "collapse" ?>" data-toggle="collapse" <?php echo $bool ? 'aria-expanded="true"' : ""; ?>>
          <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-settings" class="collapse <?php echo $bool ? "in" : "" ?>"
          <?php echo $bool ? 'aria-expanded="true"' : ""; ?>>
          <li><a href="nav-menus.php">导航菜单</a></li>
          <li><a href="slides.php">图片轮播</a></li>
          <li><a href="settings.php">网站设置</a></li>
        </ul>
      </li>
    </ul>
  </div>
  <script type="text/javascript">
    window.onload=function(){
      $("ul.nav li a").each(function(){
        if($(this).attr("href") == "<?=$currentPage?>.php"){
          this.parentNode.classList.add("active");
        }
      })
    }
  </script>