<?php 
	session_start();
	unset($_SESSION["isLogin"]);
	header("Location:login.php");

 ?>