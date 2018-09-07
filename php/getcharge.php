<?php
require("database.php");
$user_id = $_POST['user_id'];
$order = get("recharge_record","user_id",$user_id);
if($order){
    echo json_encode(['status'=>1,'data'=>$order]);
}
else echo json_encode(['status'=>0]);
?>