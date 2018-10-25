<?php
require("database.php");
$id = $_POST['id'];
$phone = $_POST['phone'];
$job_number = $_POST['job_number'];
$people_num  = $_POST['people_num'];
$item_id = $_POST['item_id'];
$pay =  $_POST['pay'];
//支付方式，0--未支付
$pay_way=0;
if(isset($_POST['pay_way']))$pay_way = $_POST['pay_way'];
$state=$_POST['state'];
$user = get("customer","openid",$id);
$dict=['1','2','3','4','5','6','7','8','9','0'];
$rnd = "";
for($i=0;$i<3;$i++)
{
    for($j=0;$j<4;$j++)
        $rnd.=$dict[rand(0,count($dict)-1)];
}
if($user)
{
    //检查账户内余额是否足够
    if($pay_way==4){
        //获取充值总额
        $str = "select sum(`recharge_record`.`charge`) AS `charge` from  `recharge_record` where ( '$id' = `recharge_record`.`user_id`)";
        $charge = sql_str($str);
        //获取消费总额,仅计算用会员卡支付并且支付完成或评价完成的订单
        $str = "select sum(`pay_amount`) AS `use` from `consumed_order` where ('$id' = `user_id`) AND (`payment_method` = 3) AND (`state` >= 4)";
        $use = sql_str($str);
        //计算目前账户内余额
        $charge=$charge[0]['charge']-$use[0]['use'];
        if($pay > $charge){
            echo json_encode(['state'=>0]);
            die();
        }
    }
    $time = date("Y md").$rnd;
    add("consumed_order",[["order_id",$time],["pay_amount",$pay],['user_id',$user[0]['ID']],['state',$state],['contact_phone',$phone]]);
    foreach($item_id as $id)
	{
        add("service_order",[['order_id',$time],['service_type',1],["item_id",$id],["job_number",$job_number]]);
    }
    if($pay_way == 0)add("appointment",[["order_id",$time],['user_num',$people_num]]);
    echo json_encode(['state'=>1]);
    die();
}

echo json_encode(['state'=>0]);