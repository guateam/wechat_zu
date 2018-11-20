<?php
require("database.php");
$openid = $_POST['openid'];
$user_id = get("customer","openid",$openid);
if($user_id)
{
    $user_id = $user_id[0]['openid'];
    $order = get("recharge_record","user_id",$user_id);
    if($order)
	{
        for($i=0;$i<count($order);$i++)
		{
            $order[$i]['generated_time']=substr($order[$i]['generated_time'],0,10);
        }
        echo json_encode(['status'=>1,'data'=>$order]);
    }
    else
	{
		echo json_encode(['status'=>-1]);
	}
}
else
{
    echo json_encode(['status'=>0]);
}
?>