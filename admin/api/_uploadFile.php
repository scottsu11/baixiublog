<?php 
	$file = $_FILES["file"];
// print_r($_FILES)
	$ext= strrchr($file["name"], ".");
	$fileName = time().rand(1000,9999).$ext;
	$bool= move_uploaded_file($file["tmp_name"],'../../static/uploads/'.$fileName);
	$response = ["code"=>0, "msg"=> "操作失败"];
	if($bool){
		$response["code"]=1;
		$response["msg"]="操作成功";
		$response["src"] = '/static/uploads/'.$fileName;
	}
	header("content-type:application/json");
	echo json_encode($response);
 ?>