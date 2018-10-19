<?php
require("database.php");
date_default_timezone_set('PRC'); 
$all_tech = get("technician");

$now = "";
if(!isset($_POST['time']))$now = date('Y-m-d H:i:s',time());
else $now = date('Y-m-d ').$_POST['time'].":00";


$result = [];
foreach($all_tech as $tech)
{
    $photos = get("technician_photo","job_number",$tech['job_number']);
    $skills = get("skill","job_number",$tech['job_number']);
    $order = get("service_order","job_number",$tech['job_number']);
    $busy = 2;
    $save_order=[];
    foreach($order as $so)
	{
        $consumed_order = get('consumed_order','order_id',$so['order_id']);
        $latest_time = "0";
        foreach($consumed_order as $co)
		{
            //如果有订单时间距离现在的时间差小于1小时，则认为该订单还未完成，繁忙
            $leap = abs(   strtotime($co['generated_time'])-strtotime($now)   );
            if($leap<3600)
			{
                $busy = 1;
                break;
            }
        }
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