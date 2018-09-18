<?php
require("database.php");
$order_id = $_POST['id'];
$co = get("consumed_order","order_id",$order_id);
$so = get("service_order","order_id",$order_id);
$shop = get("shop");
$shop = $shop[0]['phone'];
$info = [];
foreach($so as $svod){
    $tech = get("technician","job_number",$svod['job_number']);
    $svs = get("service_type","ID",$svod['item_id']);
    $appo = null;
    if($co[0]['state']==1){
        $appo = get("appointment",'order_id',$order_id);
    }
    if($tech && $svs){
        array_push($info,[
            "technician_name"=>$tech[0]['name'],
            "job_number"=>$tech[0]['job_number'],
            "service_name"=>$svs[0]['name'],
            "price"=>$svs[0]['price']*$svs[0]['discount']*0.01*0.01,
            "time"=>$co[0]['generated_time'],
            "pay_way"=>$co[0]['payment_method'],
            "user_id"=>$co[0]['user_id'],
            "appo"=>$appo,
            "phone"=>$shop,
        ]);
    }else{
        array_push($info,[
            "technician_name"=>"",
            "job_number"=>"",
            "service_name"=>"",
            "price"=>0,
            "time"=>"",
            "pay_way"=>"",
            "user_id"=>"",
            "appo"=>$appo,
            "phone"=>$shop,
        ]);
    }
}
echo json_encode($info);