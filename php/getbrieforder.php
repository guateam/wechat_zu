<?php
require("database.php");
$id = "o5C5W49NYt7U5Yl9rEBWc9esKdKg";//$_POST['openid'];
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

//$consumed_order = get("consumed_order");
// foreach($consumed_order as $co)
// {
//     //获取每个消费订单下的服务订单
//     $so = get("service_order","order_id",$co['order_id']);
//     foreach($so as $svod)
// 	{
//         //只处理显示给顾客看的订单
//         if($svod['show'] != 1)
// 		{
//             continue;
//         }
//         //获取这个服务订单下的技师信息和服务信息
//         $tech = get("technician","job_number",$svod['job_number']);
//         $svs = get("service_type","ID",$svod['item_id']);
//         //若存在该项服务和该名技师
//         if($tech && $svs)
// 		{
//             //保存信息
//             array_push($info,[
//                 "ID"=>$svod['ID'],
//                 "job_number"=>$tech[0]['job_number'],
//                 "price"=>$svs[0]['price']*$svs[0]['discount']*0.01*0.01,
//                 "order_id"=>$co['order_id'],
//                 "state"=>$co['state'],
//                 "time"=>substr($co['generated_time'],0,10)
//             ]);
//         }
// 		else
// 		{
//             //压入空信息
//             array_push($info,[
//                 "ID"=>"",
//                 "technician_name"=>"",
//                 "job_number"=>"",
//                 "price"=>0,
//                 "order_id"=>"",
//                 "state"=>"",
//                 "time"=>"",
//             ]);
//         }
//     }
// }

echo json_encode(['nostart'=>$nostart_order,'doing'=>$doing_order,
                'nopay'=>$nopay_order,'nojudge'=>$nojudge_order,
                'complete'=>$complete_order,'allorder'=>$all_order]);
