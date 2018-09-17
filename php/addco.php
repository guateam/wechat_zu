<?php
require("database.php");
$phone = $_POST['phone'];
$job_number = $_POST['job_number'];
$people_num  = $_POST['people_num'];
$item_id = $_POST['item_id'];
$user = get("customer","phone_number",$phone);
if($user){
    $time = "OD".date("Ymd");
    add("consumed_order",[["order_id",$time],['user_id',$user[0]['ID']],['state',1]]);
    foreach($item_id as $id){
        add("service_order",[['order_id',$time],['service_type',1],["item_id",$id],["job_number",$job_number]]);
    }
    add("appointment",[["order_id",$time],['user_num',$people_num]]);
}

echo json_encode(['state'=>1]);