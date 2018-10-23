<?php
require("database.php");
date_default_timezone_set('PRC'); 
$all_tech = get("technician");

$result = [];
foreach($all_tech as $tech)
{
    $jbnb = $tech['job_number'];
    $photos = get("technician_photo","job_number",$jbnb);
    $skills = get("skill","job_number",$jbnb);
    $order = get("service_order","job_number",$jbnb);
    //表示是否繁忙，1为繁忙，2为不繁忙
    $busy = 2;
    $save_order=[];
    $clock = sql_str("select * from clock WHERE (`job_number` = '$jbnb') ORDER BY `time` DESC");
    if($clock){
        if($clock[0]['state'] == 1)$busy = 1;
        else $busy = 2;
    }
    $photo_list = [];
    foreach($photos as $photo)
	{
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