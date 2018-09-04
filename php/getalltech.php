<?php
require("database.php");

$all_tech = get("technician");
$result = [];
foreach($all_tech as $tech){
    $photos = get("technician_photo","job_number",$tech['job_number']);
    $skills = get("skill","job_number",$tech['job_number']);
    $order = get("service_order","job_number",$tech['job_number']);
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
        "level"=>$tech['level'],
        "hot"=>count($order),
    ]);
}

echo json_encode(["data"=>$result]);