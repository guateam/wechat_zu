<?php
require("database.php");
$order_id = $_POST['id'];
$rate = get("rate_info","order_id",$order_id);
if($rate){
    echo json_encode(['status'=>1,'data'=>$rate]);
}else{
    echo json_encode(['status'=>0]);
}
