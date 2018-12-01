<?php 
	require_once "../config.php";
	require_once "../functions.php";
	// 接收必要的参数
	$categoryId = $_POST["categoryId"];
	$currentPage = $_POST["currentPage"];
	$pageSize = $_POST["pageSize"];
	$offset = ($currentPage-1) * $pageSize;

	$conn = connect();
	$sql="SELECT p.id,p.title,p.feature,p.created,p.content,p.views,p.likes,u.nickname,c.`name`,
	(SELECT count(id) from comments WHERE comments.post_id = p.id )as commentsCount FROM posts p
	LEFT JOIN users u ON u.id = p.user_id
	LEFT JOIN categories c ON c.id = p.category_id
	WHERE p.category_id = {$categoryId}
	LIMIT {$offset}, {$pageSize}";
	$postArr= query($conn,$sql);

	// 还要返回一个数据：数据库中当前分类下的所有文章的数量
	$countSql = "SELECT count(id) as postCount FROM posts WHERE category_id = {$categoryId}";
	$countArr = query($conn,$countSql);
	// print_r($countArr);
	$postCount = $countArr[0]["postCount"];

	$response = ["code"=>0,"msg"=>"操作失败"];
	if($postArr) {
		$response["code"] = 1;
		$response["msg"] = "操作成功";
		$response["data"] = $postArr;
		$response["postCount"] = $postCount;
	};
	header("Content-Type:application/json;charset=utf-8");
	echo json_encode($response);
 ?>