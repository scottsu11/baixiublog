<?php
    require_once "../../config.php";
    require_once "../../functions.php";
    $ids = $_POST["ids"];
    $conn = connect();
    $sql="DELETE FROM categories WHERE id in ('".implode("','",$ids)."')";
    $result= mysqli_query($conn,$sql);
    $response = ["code"=>0,"msg"=>"操作失败"];
    if($result){
        $response["code"]=1;
        $response["msg"]="操作成功";
    }
    header("content-type:application/json");
    echo json_encode($response);
?>