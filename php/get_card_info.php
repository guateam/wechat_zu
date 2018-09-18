<?php
require("database.php");
$user_id = $_POST['user_id'];
$is_vip = get("customer",'ID',$user_id);
$is_vip = $is_vip[0]['level']==0?false:true;
$result = get("recharge_info",'user_id',$user_id);
if($result && $is_vip){
    $result[0]['lvup']/=100;
    $result[0]['total_recharge']/=100;
    echo json_encode(['status'=>1,'data'=>$result[0]]);
}else{
    $first_lv = get("vip_information",'level',1);
    echo json_encode(['status'=>0,'data'=>[
        'next_lv'=>$first_lv[0]['name'],
    ]]);
}