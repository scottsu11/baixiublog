<?php 
	require_once "../../config.php";
	require_once "../../functions.php";

	$email = $_POST["email"];
	$password = $_POST["password"];
	$conn = connect();
	$sql = "SELECT * FROM users where email = '{$email}' AND `password` = '{$password}' and `status` = 'activated'";
	$queryResult = query($conn,$sql);
	// print_r($queryResult);
	$response = ["code"=>0,"msg"=>"操作失败"];
	if($queryResult){
		// 如果登录成功，应该用session把登录状态保存
		session_start();
		$_SESSION["isLogin"] = 1;
		$_SESSION["nickname"] = $queryResult[0]["nickname"];
		$_SESSION["avatar"] = $queryResult[0]["avatar"];
		$response["code"] = 1;
		$response["msg"]= "登录成功";
	}
	header("Content-Type:application/json;charset=utf-8");
	echo json_encode($response);
 ?>