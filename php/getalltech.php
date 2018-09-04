<?php
require("database.php");

$all_tech = get("technician");
$result = [];
foreach($all_tech as $tech){
    $photos = get("technician_photo","job_number",$tech['job_number']);
    $skills = get("skill","job_number",$tech['job_number']);
    $order = get("service_order","job_number",$tech['job_number']);
    $busy = 2;
    $save_order=[];
    foreach($order as $so){
        $consumed_order = get('consumed_order','order_id',$so['order_id']);
        $latest_time = "0";
        foreach($consumed_order as $co){
            if($co['generated_time']>$latest_time)$latest_time = $co['generated_time'];
        }
        $latest_time = strtotime($latest_time);
        $now_time = date('Y-m-d H:i:s');
        $now_time = strtotime($now_time);
        $leap  = $now_time-$latest_time;
        if($leap<3600){
            $busy = 1;
        }
    }
    $photo_list = [];
    foreach($photos as $photo){
        array_push($photo_list,str_replace('..','/wechat_zu_technician/',$photo['img']));
    }
    array_push($result,[
        "avatar_url"=>$tech['photo'],
        "job_number"=>$tech['job_number'],
        "description"=>$tech['description'],
        "photo_list"=>$photo_list,
        "rate"=>$tech['rate'],
        "skill"=>$skills,
        'busy'=>$busy,
        "level"=>$tech['level'],
        "hot"=>count($order),
    ]);
}

echo json_encode(["data"=>$result]);