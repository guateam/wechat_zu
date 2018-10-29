<?php
require("database.php");
date_default_timezone_set('PRC'); 
$numbers = [];
$tech_info = sql_str("select A.job_number,A.photo from technician A");
foreach($tech_info as $idx => $tc){
    $job_number = $tc['job_number'];
    //获取图片
    $friend_circle = sql_str("select A.img from technician_photo A where A.job_number = '$job_number' order by A.ID desc limit 3");
    //获取评分
    $rate = sql_str("select Avg(score) as score from rate where job_number = '$job_number' and `bad` = 1");
    //保留一位小数
    $rate = round($rate[0]['score'],1);
    $tech_info[$idx] = array_merge($tech_info[$idx],['img_list'=>$friend_circle,'rate'=>$rate,'level'=>""]);
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