<?php
require("database.php");
$order_id = $_POST['order_id'];
if($order_id){
    set("consumed_order","order_id",$order_id,[['state',1]]);
    echo json_encode(['status'=>1]);
}