<?php
require("database.php");
$id = $_POST['id'];
$phone = $_POST['phone'];
$people_num  = $_POST['people_num'];
$pay =  $_POST['pay'];
$obj = $_POST['obj'];
$appoint_time = $_COOKIE['select_time'];
//支付方式，0--未支付
$pay_way=0;
if(isset($_POST['pay_way']))$pay_way = $_POST['pay_way'];

//外部传入的订单状态
$state=$_POST['state'];
$user = get("customer","openid",$id);
$dict=['1','2','3','4','5','6','7','8','9','0'];
$rnd = "";
for($i=0;$i<3;$i++)
{
    for($j=0;$j<4;$j++)
        $rnd.=$dict[rand(0,count($dict)-1)];
}
$time = date("Ymd").$rnd;
if($user)
{
    //会员卡支付
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
            //添加未支付订单
            add("consumed_order",[['appoint_time',$tm],['user_num',$people_num],['generated_time',time()],["order_id",$time],["pay_amount",$pay],['user_id',$user[0]['ID']],['state',0],['contact_phone',$phone]]);
            //添加服务
            foreach($obj as $tech)
            {
                $jbnb = $tech['tech']['job_number'];
                $service_id = $tech['service']['ID'];
                add("service_order",[['appoint_time',$tm],['order_id',$time],['service_type',1],["item_id",$service_id],["job_number",$jbnb]]);
            }
            echo json_encode(['state'=>-1,'order_id'=>$time]);
            die();
        }
    }else{
        //其他支付方式，扣款由其他api执行成功后才执行到这里，不需要判断是否扣钱了，直接添加订单
        //默认添加为微信支付，payment_method为1
        add("consumed_order",[['appoint_time',$tm],['user_num',$people_num],['payment_method',1],['generated_time',time()],["order_id",$time],["pay_amount",$pay],['user_id',$user[0]['ID']],['state',$state],['contact_phone',$phone]]);
        //添加服务
        foreach($obj as $tech)
        {
            $jbnb = $tech['tech']['job_number'];
            $service_id = $tech['service']['ID'];
            add("service_order",[['appoint_time',$tm],['order_id',$time],['service_type',1],["item_id",$service_id],["job_number",$jbnb]]);
        }
        echo json_encode(['state'=>1,'order_id'=>$time]);
        die();
    }

}
//用户不存在的情况
echo json_encode(['state'=>0]);