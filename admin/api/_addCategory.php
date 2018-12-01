<?php 
	require_once "../../config.php";
	require_once "../../functions.php";

	$name = $_POST["name"];
	$conn = connect();
	$sql = "SELECT count(*) as count FROM categories WHERE `name`='{$name}'";
	$countResult = query($conn,$sql);
	$count = $countResult[0]["count"];
	$response = ["code"=>0, "msg"=> "操作失败"];
	if($count > 0){
		$response["msg"] = "分类名称已经存在，不能重复添加";
	}else {
		$slug = $_POST["slug"];
		$classname = $_POST["classname"];
		$addSql = "INSERT INTO categories (`name`,slug,classname) VALUES ('{$name}','{$slug}','{$classname}')";
		$addResult = mysqli_query($conn,$addSql);
		if($addResult) {
			$response["code"]=1;
			$response["msg"]="操作成功";
		}
	};
	header("content-type:application/json;charset= utf-8");
	echo json_encode($response);
 ?>