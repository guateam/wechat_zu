<?php
require("database.php");
//获取所有消费订单
$consumed_order = sql_str("select * from `consumed_order` ORDER BY `generated_time` DESC");
//$consumed_order = get("consumed_order");
$info_rev = [];
$info = [];
foreach($consumed_order as $co)
{
    //获取每个消费订单下的服务订单
    $so = get("service_order","order_id",$co['order_id']);
    foreach($so as $svod)
	{
        //只处理显示给顾客看的订单
        if($svod['show'] != 1)
		{
            continue;
        }
        //获取这个服务订单下的技师信息和服务信息
        $tech = get("technician","job_number",$svod['job_number']);
        $svs = get("service_type","ID",$svod['item_id']);
        //若存在该项服务和该名技师
        if($tech && $svs)
		{
            //保存信息
            array_push($info,[
                "ID"=>$svod['ID'],
                "job_number"=>$tech[0]['job_number'],
                "price"=>$svs[0]['price']*$svs[0]['discount']*0.01*0.01,
                "order_id"=>$co['order_id'],
                "state"=>$co['state'],
                "time"=>substr($co['generated_time'],0,10)
            ]);
        }
		else
		{
            //压入空信息
            array_push($info,[
                "ID"=>"",
                "technician_name"=>"",
                "job_number"=>"",
                "price"=>0,
                "order_id"=>"",
                "state"=>"",
                "time"=>"",
            ]);
        }
    }
}

echo json_encode($info);
