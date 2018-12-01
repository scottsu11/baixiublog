<?php 
	require_once "../../config.php";
	require_once "../../functions.php";
	$id = $_POST["id"];
	$name = $_POST["name"];
	$slug = $_POST["slug"];
	$classname = $_POST["classname"];
	$conn = connect();
	$sql = "UPDATE categories SET `name` = '{$name}',slug = '{$slug}',classname = '{$classname}' WHERE id = '{$id}'";
	$result = mysqli_query($conn,$sql);
	$response = ["code"=>0, "msg"=>"操作失败"];
	if($result){
		$response["code"]=1;
		$response["msg"]="操作成功";
	}
	header("content-type:application/json;charset=utf-8");
	echo json_encode($response);
 ?>