<?php
require("database.php");
date_default_timezone_set('PRC'); 
$numbers = [];


//计算目前的时间距离下一个5分钟还有多少秒
$to_5min = 300-time()%300;
////获取选择的时间，若未选择时间，则默认为当前时间的下一个5分钟整
$select_time = time()+$to_5min;
//查找的技师类别，1--普通技师   2--接待人员  3--茶艺师,默认为普通技师
$type = null;

if(isset($_POST['select_time'])){
    $select_time = intval($_POST['select_time']);
    if($select_time == "")$select_time = time();
}
if(isset($_POST['type'])){
    $type = $_POST['type'];
}
$tech_info = [];
if(!is_null($type)){
    if($type == 1){
        $tech_info = sql_str("select A.busy,A.job_number,A.photo,B.level from technician A,skill B where `type`='$type' and B.job_number=A.job_number group by A.job_number");
    }else{
        $tech_info = sql_str("select A.busy,A.job_number,A.photo from technician A where `type`='$type' ");
    }
}
else{
    $tech_info = sql_str("select A.busy,A.job_number,A.photo from technician A");
}

foreach($tech_info as $idx => $tc){
    $job_number = $tc['job_number'];
    //获取预约情况
    //在选择的时间以后最近订单被预约并且时间间隔在1个半小时以内,并且订单在预约状态或服务中状态的
    $appoint_after = sql_str("select A.* from service_order A,consumed_order B where  B.order_id = A.order_id and (B.state=1 or B.state=2) and A.appoint_time >= $select_time and A.appoint_time-90*60 <= $select_time and A.job_number='$job_number' order by (A.appoint_time - $select_time) ASC");
    //在选择的时间之前最近订单被预约并且时间间隔在1个半小时以内的,并且订单在预约状态或服务中状态的
    $appoint_before = sql_str("select A.* from service_order A,consumed_order B where  B.order_id = A.order_id and (B.state=1 or B.state=2) and A.appoint_time <= $select_time and A.appoint_time+90*60 >= $select_time and A.job_number='$job_number' order by ($select_time - A.appoint_time ) ASC");
    //是否被预约
    $already_appoint = false;
    //若有预约
    if($appoint_after || $appoint_before){
        $tech_info[$idx]['busy'] = 1;
    }

    //获取图片
    $friend_circle = sql_str("select A.img from technician_photo A where A.job_number = '$job_number' order by A.ID desc limit 3");
    //获取评分
    $rate = sql_str("select Avg(score) as score from rate where job_number = '$job_number' and `bad` = 1");
    //保留一位小数
    $rate = round($rate[0]['score'],1);
    //$tech_info[$idx] = array_merge($tech_info[$idx],['img_list'=>$friend_circle,'rate'=>$rate,'level'=>"",'busy'=>$up_clock,'appoint'=>$already_appoint]);
    $tech_info[$idx] = array_merge($tech_info[$idx],['img_list'=>$friend_circle,'rate'=>$rate]);
}
echo json_encode(['status'=>1,'data'=>$tech_info]);




// $all_tech = get("technician");

// $result = [];
// foreach($all_tech as $tech)
// {
//     $jbnb = $tech['job_number'];
//     $photos = get("technician_photo","job_number",$jbnb);
//     $skills = get("skill","job_number",$jbnb);
//     $order = get("service_order","job_number",$jbnb);
//     //表示是否繁忙，1为繁忙，2为不繁忙
//     $busy = 2;
//     $save_order=[];
//     $clock = sql_str("select * from clock WHERE (`job_number` = '$jbnb') ORDER BY `time` DESC");
//     if($clock){
//         if($clock[0]['state'] == 1)$busy = 1;
//         else $busy = 2;
//     }
//     $photo_list = [];
//     // foreach($photos as $photo)
// 	// {
//     //     array_push($photo_list,str_replace('..','/wechat_zu_technician/',$photo['img']));
//     // }
//     array_push($result,[
//         "avatar_url"=>$tech['photo'],
//         "job_number"=>$tech['job_number'],
//         "description"=>$tech['description'],
//         "photo_list"=>$photos,
//         "rate"=>$tech['rate'],
//         "skill"=>$skills,
//         'busy'=>$busy,
//         "level"=>$tech['level'],
//         "hot"=>count($order),
//     ]);
// }
// echo json_encode(["data"=>$result]);