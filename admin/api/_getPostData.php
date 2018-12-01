<?php 
	require_once "../../config.php";
	require_once "../../functions.php";
	$currentPage = $_POST["currentPage"];
	$pageSize = $_POST["pageSize"];
	$status = $_POST["status"];
	$categoryId = $_POST["categoryId"];

	$offset = ($currentPage-1) * $pageSize;
	$conn = connect();
	$where = "where 1 =1";
	if($status != "all"){
		$where .= " and p.status = '{$status}'";
	}
	if($categoryId != "all"){
		$where .= " and p.category_id = '{$categoryId}'";
	}
	$sql="SELECT p.id,p.title,p.created,p.`status`,u.nickname,c.`name` FROM posts p
	LEFT JOIN users u ON u.id = p.user_id
	LEFT JOIN categories c ON c.id=p.category_id ".$where." LIMIT {$offset},{$pageSize}";
	$result = query($conn,$sql);

	// 查询文章总数的sql语句
	$countSql = "SELECT count(id) as count FROM posts";
	$countResult = query($conn,$countSql);
	$postCount = $countResult[0]["count"];
	// 根据pageSize计算出总共有多少页
	$pageCount = ceil($postCount/$pageSize);
	$response = ["code"=>0, "msg"=>"操作失败"];
	if($result){
		$response["code"]=1;
		$response["msg"]="操作成功";
		$response["data"]=$result;
		$response["pageCount"] = $pageCount;
	}
	header("content-type:application/json;charset=utf-8");
	echo json_encode($response);
 ?>