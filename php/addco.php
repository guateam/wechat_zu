<?php
require("database.php");
$id = $_POST['id'];
$phone = $_POST['phone'];
$people_num  = $_POST['people_num'];
$pay =  $_POST['pay'];
$obj = $_POST['obj'];
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
    $time = date("Ymd").$rnd;
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
        //余额不足的情况
        if($pay > $charge){
            //添加预约订单
            add("consumed_order",[['generated_time',time()],["order_id",$time],["pay_amount",$pay],['user_id',$user[0]['ID']],['state',1],['contact_phone',$phone]]);
            //添加服务
            foreach($obj as $tech)
            {
                $jbnb = $tech['tech']['job_number'];
                $service_id = $tech['service']['ID'];
                add("service_order",[['order_id',$time],['service_type',1],["item_id",$service_id],["job_number",$jbnb]]);
            }
            //添加预约状态
            add("appointment",[["order_id",$time],['user_num',$people_num]]);
            echo json_encode(['state'=>-1]);
            die();
        }
    }
    //添加订单
    add("consumed_order",[['generated_time',time()],["order_id",$time],["pay_amount",$pay],['user_id',$user[0]['ID']],['state',$state],['contact_phone',$phone]]);
    //添加服务
    foreach($obj as $tech)
    {
        $jbnb = $tech['tech']['job_number'];
        $service_id = $tech['service']['ID'];
        add("service_order",[['order_id',$time],['service_type',1],["item_id",$service_id],["job_number",$jbnb]]);
    }
    //若是未支付的情况，添加预约状态
    if($pay_way == 0)add("appointment",[["order_id",$time],['user_num',$people_num]]);
    echo json_encode(['state'=>1]);
    die();
}
//用户不存在的情况
echo json_encode(['state'=>0]);