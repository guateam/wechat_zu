<?php
require("database.php");
$consumed_order = get("consumed_order");
$info = [];
foreach($consumed_order as $co){
    $so = get("service_order","order_id",$co['order_id']);
    foreach($so as $svod){
        $tech = get("technician","job_number",$svod['job_number']);
        $svs = get("service_type","ID",$svod['item_id']);
        if($tech && $svs){
            array_push($info,[
                "job_number"=>$tech[0]['job_number'],
                "price"=>$svs[0]['price']*$svs[0]['discount']*0.01*0.01,
                "order_id"=>$co['order_id'],
                "state"=>$co['state'],
                "time"=>substr($co['generated_time'],0,10)
            ]);
        }else{
            array_push($info,[
                "technician_name"=>"",
                "job_number"=>"",
                "price"=>0,
                "order_id"=>"",
                "state"=>"",
                "time"=>"",
            ]);
        }
    }
}
echo json_encode($info);
