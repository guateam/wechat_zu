<?php
require("database.php");
$job_number = $_POST['job_number'];
$skill =  get("skill","job_number",$job_number);
$phone = (get("shop"))[0]['phone'];
$data = [];
if($skill)
{
    foreach($skill as $sk)
	{
        $sv = get("service_type","ID",$sk['service_id']);
        array_push($data,[
            'name'=>$sv[0]['name'],
            'ID'=>$sv[0]['ID'],
            'duration'=>$sv[0]['duration'],
            'price'=>($sv[0]['price'] + $sk['extra_income'])/100,
            'job_number'=>$sk['job_number'],
        ]);
    }
    echo json_encode(['status'=>1,'data'=>$data,'phone'=>$phone]);
}
else
{
    echo json_encode(['status'=>0,'data'=>[]]);
}