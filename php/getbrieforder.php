<?php
require("database.php");
$id = $_POST['openid'];
//获取所有消费订单
$all_order = sql_str("select * from `consumed_order` where `user_id`='$id' ORDER BY `generated_time` DESC");
//待消费订单
$nostart_order = sql_str("select * from `consumed_order` where `state`=1 and `user_id`='$id' ORDER BY `generated_time` DESC");
//服务中订单
$doing_order =  sql_str("select * from `consumed_order` where `state`=2 and `user_id`='$id' ORDER BY `generated_time` DESC");
//待支付订单
$nopay_order = sql_str("select * from `consumed_order` where `state`=3 and `user_id`='$id' ORDER BY `generated_time` DESC");
//待评价订单
$nojudge_order = sql_str("select * from `consumed_order` where `state`=4 and `user_id`='$id' ORDER BY `generated_time` DESC");
//评价完成订单
$complete_order = sql_str("select * from `consumed_order` where `state`=5 and `user_id`='$id' ORDER BY `generated_time` DESC");

echo json_encode(['nostart'=>$nostart_order,'doing'=>$doing_order,
                'nopay'=>$nopay_order,'nojudge'=>$nojudge_order,
                'complete'=>$complete_order,'allorder'=>$all_order]);
?>