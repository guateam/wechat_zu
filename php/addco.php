<?php
require("database.php");
$id = $_POST['id'];
$phone = $_POST['phone'];
$job_number = $_POST['job_number'];
$people_num  = $_POST['people_num'];
$item_id = $_POST['item_id'];
$pay =  $_POST['pay'];
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
    $time = date("Y md").$rnd;
    add("consumed_order",[["order_id",$time],["pay_amount",$pay],['user_id',$user[0]['ID']],['state',1],['contact_phone',$phone]]);
    foreach($item_id as $id)
	{
        add("service_order",[['order_id',$time],['service_type',1],["item_id",$id],["job_number",$job_number]]);
    }
    add("appointment",[["order_id",$time],['user_num',$people_num]]);
}

echo json_encode(['state'=>1]);