<?php 
	require_once "../../config.php";
	require_once "../../functions.php";

	$conn = connect();
	$sql = "SELECT * FROM categories";
	$queryResult = query($conn,$sql);
	$response = ["code"=> 0, "msg"=> "操作失败"];
	if($queryResult){
		$response["code"] =1;
		$response["msg"] ="操作成功";
		$response["data"] =$queryResult;
	}
	header("content-type:application/json;charset=utf-8");
	echo json_encode($response);
 ?>