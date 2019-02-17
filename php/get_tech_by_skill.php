<?php
require 'database.php';
$id = $_POST['service_id'];

//type表示要查询的技师类别  1--普通技师  2--接待员  3--茶艺师
$type = 1;
if (isset($_POST['type'])) {
    $type = $_POST['type'];
}
$numbers = [];

//获取选择的时间，若未选择时间，则默认为当前时间
$select_time = time();
if (isset($_POST['select_time'])) {
    $select_time = intval($_POST['select_time']);
    if ($select_time == "") {
        $select_time = time();
    }

}
//获取服务或茶水,技师或茶艺师
$service_name = [];
if ($type == 1) {
    $service_name = sql_str("select name,have_level from service_type where `ID` = '$id'");
    $tech_info = sql_str("select A.job_number,A.photo,B.level,C.name from technician A,skill B,skill_level C where A.job_number = B.job_number and B.service_id = '$id' and C.ID = B.level");
    $no_level = sql_str("select A.job_number,A.photo,B.level from technician A,skill B where A.job_number = B.job_number and B.service_id = '$id' and B.level = 0");
    for ($i = 0; $i < count($no_level); $i++) {
        $no_level[$i] = array_merge($no_level[$i], ['name' => ""]);
    }
    $tech_info = array_merge($tech_info, $no_level);

} else if ($type == 3) {
    $service_name = sql_str("select name from item_type where `ID` = '$id'");
    $tech_info = sql_str("select A.job_number,A.photo from technician A where A.type=3");
}

// $friend_circle = sql_str("select ")
foreach ($tech_info as $idx => $tc) {
    $job_number = $tc['job_number'];
    //是否在上钟
    $up_clock = false;
    //是否被预约
    $already_appoint = false;

    //获取刷钟情况
    $clock = sql_str("select state from clock where job_number = '$job_number' order by `time` limit 1");

    //若有刷钟记录
    if ($clock) {
        //若最近的刷钟记录为上钟，则上钟情况为true
        if ($clock[0]['state'] == 1) {
            $up_clock = true;
        }

    }
    // //获取该技师会的技能里面最耗时的服务
    // $most_cost_time = sql_str("select MAX(B.duration) as duration from (select service_id from skill where job_number = '$job_number') A,service_type B where A.service_id = B.ID");
    // if ($most_cost_time) {
    //     $most_cost_time = $most_cost_time[0]['duration'];
    // } else {
    //     $most_cost_time = 0;
    // }
    // //查看当前预约的时间是否在某个服务之间的开始和结束时间之内
    // $doing_order = sql_str("select * from service_order A,service_type B where B.ID = A.item_id and A.job_number = '$job_number' and A.appoint_time+B.duration*60 > $select_time and A.appoint_time < $select_time");
    // //若预约的时间空闲，则将当前时间加上该技师会的技能里面最耗时的服务时间之后，查看新的时间下是否空闲，若仍然空闲，则可以预约，否则无法预约
    // if (!$doing_order) {
    //     $will_hit = sql_str("select * from service_order A,service_type B where B.ID = A.item_id and A.job_number = '$job_number' and $select_time+$most_cost_time*60 >= A.appoint_time and $select_time+$most_cost_time*60 <= A.appoint_time+B.duration*60");
    //     //若不会和之后存在的服务重叠时间，则可以预约
    //     if (!$will_hit) {
    //         $already_appoint = false;
    //     } else {
    //         $already_appoint = true;
    //     }
    // }
    // //若预约的时间不空闲，则无法预约
    // else {
    //     $already_appoint = true;
    // }
    $appoint_tech = sql_str("select * from service_order where job_number = '$job_number' and appoint_time > ($select_time - (select Sum(duration)*60 from service_order A,service_type B where A.item_id = B.ID and A.order_id =(select order_id from service_order where job_number = '$job_number' and appoint_time < $select_time order by appoint_time desc limit 1)  ))");
    if($appoint_tech){
        $already_appoint = true;
    }else{
        $already_appoint = false;
    }
    $friend_circle = sql_str("select A.img from technician_photo A where A.job_number = '$job_number' order by A.ID desc limit 3");
    $rate = sql_str("select Avg(score) as score from rate where job_number = '$job_number' and `bad` = 1");
    //保留一位小数
    $rate = round($rate[0]['score'], 1);
    $tech_info[$idx] = array_merge($tech_info[$idx], ['img_list' => $friend_circle, 'rate' => $rate, 'busy' => $up_clock, 'appoint' => $already_appoint]);
    //只有是普通技师时，才有等级的处理
    if ($type == 1 && $service_name[0]['have_level'] == 0) {
        $tech_info[$idx]['level'] = "";
    }

}
$svnm = $service_name[0]['name'];
echo json_encode(['status' => 1, 'data' => $tech_info, 'service_name' => $svnm, 'have_level' => $type == 1 ? $service_name[0]['have_level'] : 0]);
