<?php
require("database.php");
$user_id = $_POST['user_id'];
$result = get("recharge_info",'user_id',$user_id);
if($result){
    $result[0]['lvup']/=100;
    $result[0]['total_recharge']/=100;
    echo json_encode(['status'=>1,'data'=>$result[0]]);
}else{
    echo json_encode(['status'=>0]);
}