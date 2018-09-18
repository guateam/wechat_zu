<?php
require("database.php");
$phone = $_POST['phone'];
$job_number = $_POST['job_number'];
$people_num  = $_POST['people_num'];
$item_id = $_POST['item_id'];
$user = get("customer","phone_number",$phone);
$dict=['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
        '1','2','3','4','5','6','7','8','9','0'];
$rnd = "";
for($i=0;$i<5;$i++){
    $rnd.=$dict[rand(0,count($dict)-1)];
}
if($user){
    $time = "OD".date("Ymd").$rnd;
    add("consumed_order",[["order_id",$time],['user_id',$user[0]['ID']],['state',1]]);
    foreach($item_id as $id){
        add("service_order",[['order_id',$time],['service_type',1],["item_id",$id],["job_number",$job_number]]);
    }
    add("appointment",[["order_id",$time],['user_num',$people_num]]);
}

echo json_encode(['state'=>1]);