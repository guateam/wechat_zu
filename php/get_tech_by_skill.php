<?php
require 'database.php';
$id = $_POST['service_id'];

//type表示要查询的技师类别  1--普通技师  2--接待员  3--茶艺师
$type = 1;
if (isset($_POST['type'])) 
{
    $type = $_POST['type'];
}
$numbers = [];

//获取选择的时间，若未选择时间，则默认为当前时间
$select_time = time();
if (isset($_POST['select_time'])) 
{
    $select_time = intval($_POST['select_time']);
    if ($select_time == "") 
	{
        $select_time = time();
    }

}
//获取服务或茶水,技师或茶艺师
$service_name = [];
if ($type == 1) 
{
    $service_name = sql_str("select name,have_level from service_type where `ID` = '$id'");
    $tech_info = sql_str("select A.busy,A.job_number,A.photo,B.level,C.name from technician A,skill B,skill_level C where A.job_number = B.job_number and B.service_id = '$id' and C.ID = B.level");
    $no_level = sql_str("select A.job_number,A.photo,B.level from technician A,skill B where A.job_number = B.job_number and B.service_id = '$id' and B.level = 0");
    for ($i = 0; $i < count($no_level); $i++) 
	{
        $no_level[$i] = array_merge($no_level[$i], ['name' => ""]);
    }
    $tech_info = array_merge($tech_info, $no_level);

}
else if ($type == 3) 
{
    $service_name = sql_str("select name from item_type where `ID` = '$id'");
    $tech_info = sql_str("select A.busy,A.job_number,A.photo from technician A where A.type=3");
}

// $friend_circle = sql_str("select ")
foreach ($tech_info as $idx => $tc) 
{
    $job_number = $tc['job_number'];

    //在选择的时间以后最近订单被预约并且时间间隔在1个半小时以内,并且订单在预约状态或服务中状态的
    $appoint_after = sql_str("select A.* from service_order A,consumed_order B where  B.order_id = A.order_id and (B.state=1 or B.state=2) and A.appoint_time >= $select_time and A.appoint_time-90*60 <= $select_time and A.job_number='$job_number' order by (A.appoint_time - $select_time) ASC");
    //在选择的时间之前最近订单被预约并且时间间隔在1个半小时以内的,并且订单在预约状态或服务中状态的
    $appoint_before = sql_str("select A.* from service_order A,consumed_order B where  B.order_id = A.order_id and (B.state=1 or B.state=2) and A.appoint_time <= $select_time and A.appoint_time+90*60 >= $select_time and A.job_number='$job_number' order by ($select_time - A.appoint_time ) ASC");
    
    //是否被预约
    $already_appoint = false;
    if($appoint_before || $appoint_after)
	{
       $tech_info[$idx]['busy'] = 1;
    }
    $friend_circle = sql_str("select A.img from technician_photo A where A.job_number = '$job_number' order by A.ID desc limit 3");
    $rate = sql_str("select Avg(score) as score from rate where job_number = '$job_number' and `bad` = 1");
    //保留一位小数
    $rate = round($rate[0]['score'], 1);
    $tech_info[$idx] = array_merge($tech_info[$idx], ['img_list' => $friend_circle, 'rate' => $rate, 'appoint' => $already_appoint]);
    
    
    //只有是普通技师时，才有等级的处理
    if ($type == 1 && $service_name[0]['have_level'] == 0) 
	{
        $tech_info[$idx]['level'] = "";
    }
}
$svnm = $service_name[0]['name'];
echo json_encode(['status' => 1, 'data' => $tech_info, 'service_name' => $svnm, 'have_level' => $type == 1 ? $service_name[0]['have_level'] : 0]);

?>
