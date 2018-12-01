<?php 
  // 连接数据库
  $conn = connect();
  $sql = "select * from categories where id != 1";
  // 获取结果集
  // $result = mysqli_query($conn,$sql);
  // // 把数据集转换为二维数组
  // $arr = [];
  // // 从结果集中取得一行作为关联数组
  // while ($row = mysqli_fetch_assoc($result)) {
  //   $arr[]=$row;
  // }
  $arr=query($conn,$sql);
  // print_r($arr);
 ?>

<div class="header">
      <h1 class="logo"><a href="index.php"><img src="static/assets/img/logo.png" alt=""></a></h1>
      <ul class="nav">
        <!-- <li><a href="javascript:;"><i class="fa fa-glass"></i>奇趣事</a></li>
        <li><a href="javascript:;"><i class="fa fa-phone"></i>潮科技</a></li>
        <li><a href="javascript:;"><i class="fa fa-fire"></i>会生活</a></li>
        <li><a href="javascript:;"><i class="fa fa-gift"></i>美奇迹</a></li> -->
       <?php 
          foreach ($arr as $value) { ?>
            <li><a href="list.php?categoryId=<?=$value["id"]?>"><i class="fa <?php echo $value["classname"]?>"></i><?php echo $value["name"]?></a></li>
          <?php }
       
        ?>
      </ul>
      <div class="search">
        <form>
          <input type="text" class="keys" placeholder="输入关键字">
          <input type="submit" class="btn" value="搜索">
        </form>
      </div>
      <div class="slink">
        <a href="javascript:;">链接01</a> | <a href="javascript:;">链接02</a>
      </div>
    </div>
