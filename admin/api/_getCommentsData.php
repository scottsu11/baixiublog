<?php 
	require_once "../../config.php";
	require_once "../../functions.php";
	$currentPage = $_POST["currentPage"];
	$pageSize = $_POST["pageSize"];
	$offset = ($currentPage-1)*$pageSize;
	$conn = connect();
	$sql = "SELECT c.id,c.author,c.content,c.created,c.`status`,p.title FROM comments c
	LEFT JOIN posts p ON p.id = c.post_id
	LIMIT {$offset},{$pageSize}";
	$result = query($conn,$sql);

	$countSql = "SELECT count(*) as count FROM comments";
	$countArr = query($conn,$countSql);
	$commentsCount = $countArr[0]["count"];
	$pageCount = ceil($commentsCount / $pageSize);
	
	$response =["code"=>0, "msg"=>"操作失败"];
	if($result){
		$response["code"]=1;
		$response["msg"]="操作成功";
		$response["data"]=$result;
		$response["pageCount"] = $pageCount;
	}
	header("content-type:application/json");
	echo json_encode($response);
 ?>