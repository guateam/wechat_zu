<?php
date_default_timezone_set('PRC');
require("database.php");
$id = $_POST['id'];
$phone = $_POST['phone'];
$people_num  = $_POST['people_num'];
$pay =  $_POST['pay'];
$obj = $_POST['obj'];//obj改成以字符串输入
$obj = explode('-',$obj);

$appoint_time = $_POST['select_time'];
$appotype = $_POST['service_type']; // 预约类型  1--服务   3--茶艺
//是否有完成支付
$cant_pay = false;
if(isset($_POST['cant_pay']))$cant_pay = $_POST['cant_pay'];

//支付方式，0--未支付，1--微信支付，3--会员卡支付
$pay_way = 0;
if(isset($_POST['pay_way']))$pay_way = $_POST['pay_way'];

//外部传入的订单状态，1--待消费  4--待评价
$state=$_POST['state'];

//根据openid获取用户的信息
$user = get("customer","openid",$id);

//创建订单号
$dict=['1','2','3','4','5','6','7','8','9','0'];
$rnd = "";
for($i=0;$i<3;$i++)
{
    for($j=0;$j<4;$j++)
	{
        $rnd.=$dict[rand(0,count($dict)-1)];
	}
}
$time = date("Ymd").$rnd;

//若能查找到用户，则继续处理
if($user)
{
        //添加订单
        add("consumed_order",[
            ['appoint_time',$appoint_time],
            ['user_num',$people_num],
            ['generated_time',time()],
            ["order_id",$time],
            ["pay_amount",$pay],
			["shouldpay_amount",$pay],
            ['user_id',$user[0]['openid']],
            ['state',$state],//状态为1，表示待消费,3表示待支付，4表示待评价
            ['contact_phone',$phone],
            ['payment_method',$pay_way] //未支付的时候，支付方式为0，表示未支付
        ]);
        //添加服务
        for($i=0;$i<count($obj)/2;$i++)
        {
            $j = $i*2;
            $jbnb = $obj[$j];
            $service_id = $obj[$j+1];

            $service_type = sql_str("select * from service_type where ID='$service_id' ");
            $technician = sql_str("select * from technician where job_number='$jbnb'");

            $ticheng = 0;
            $yongjin = 0;
			
			//$price = $service_type[0]['market_price'];//门市价
			$price = $service_type[0]['price'];//活动价

            $yongjin = $service_type[0]['invite_income'];
            //都是点钟
            
            if($technician[0]['type']==1)//技师
			{
                $ticheng = $service_type[0]['commission'];            
            }
			else if($technician[0]['type']==2)//接待
			{
                $ticheng = $service_type[0]['commission2'];
            }			
			
            add("service_order",[['appoint_time',$appoint_time],['ticheng',$ticheng],['yongjin',$yongjin], ['price',$price],['clock_type',2],['order_id',$time],['service_type',$appotype],["item_id",$service_id],["job_number",$jbnb]]);
        }
        echo json_encode(['state'=>1,'order_id'=>$time]);
        die();
}
//用户不存在的情况
echo json_encode(['state'=>0]);

?>