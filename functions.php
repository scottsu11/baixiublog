<?php 

	// 连接数据库的封装
	function connect(){
		$connect = mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_NAME);
		return $connect;
	}
	// 封装执行查询语句
	function query($connect,$sql){
		$result = mysqli_query($connect,$sql);
		return fetch($result);
	}
	//封装将结果集转换为二维数组
	function fetch($result){
		$arr=[];
		while ($row = mysqli_fetch_assoc($result)) {
			$arr[]=$row;
		}
		return $arr;
	}
	
	// 验证用户是否已经登录的函数封装
	function checkLogin(){
		session_start();
		if(!isset($_SESSION["isLogin"]) || $_SESSION["isLogin"] != 1){
			header("Location:login.php");
		}
	}
 ?>