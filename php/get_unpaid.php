<?php
require("database.php");
$order_id = null;
if(isset($_POST['id']))$order_id = $_POST['id'];
$openid = $_POST['openid'];
//查询店铺信息，目前只查找ID为1的店铺
$shop_info = sql_str("select name,position,phone from shop where ID = 1");

if(!is_null($order_id)){
    //查找该订单号的订单
    $orders = sql_str("select A.appoint_time,B.item_id,B.job_number,B.service_type from consumed_order A,service_order B where A.order_id = '$order_id' and B.order_id = A.order_id and A.user_id='$openid'");
    if($orders){
        //计算总共需要消费多少钱
        $total = 0;
        foreach($orders as $idx => $order){
            $item_id = $order['item_id'];
            //1--普通服务  3--茶艺
            if($order['service_type'] == 1){
                //查询该服务的价格等等信息               
                $svtp = sql_str("select ID,name,duration,price from service_type where `ID`='$item_id'");
                //若查到，将信息合并
                if($svtp){
                    $svtp = $svtp[0];
                    $orders[$idx] = array_merge($orders[$idx],$svtp);
                }
            }else if($order['service_type'] == 3){
                //查询该茶艺的价格等等信息               
                $svtp = sql_str("select ID,name,price from item_type where `ID`='$item_id'");
                //若查到，将信息合并
                if($svtp){
                    $svtp = $svtp[0];
                    //添加无用的持续时间字段，方便前台处理
                    $svtp = array_merge($svtp,['duration'=>0]);
                    $orders[$idx] = array_merge($orders[$idx],$svtp);
                }
            }
            $total+=$orders[$idx]['price'];
        }
        echo json_encode(['status'=>1,'data'=>$orders,'shop'=>$shop_info[0],'total'=>$total]);
        exit();
    }
    else{
        echo json_encode(['status'=>0]);
        exit();
    }
}else{
    $orders = sql_str("select * from consumed_order where `state` = 1 and user_id='$openid'");
    foreach($orders as $idx => $order){
        $odid = $order['order_id'];
        $total = sql_str("select Sum(B.price) as total from service_order A,service_type B where A.order_id = '$odid' and B.ID = A.item_id ");
        $orders[$idx] = array_merge($order,$total[0]);
    }
    echo json_encode(['status'=>1,'data'=>$orders,'shop'=>$shop_info[0]]);
    exit();
}

