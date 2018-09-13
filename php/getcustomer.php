<?php
require("database.php");
$phone = $_POST['phone'];
$cus = get("customer","phone_number",$phone);
if($cus){
    $lv = get("vip_information","level",$cus[0]['level']);
    if($lv){
        $lv = $lv[0]['name'];
    }else{
        $lv="非会员";
    }
    echo json_encode([
        'status'=>1,
        'name'=>$cus[0]['name'],
        'head'=>$cus[0]['head'],
        'phone_number'=>$cus[0]['phone_number'],
        'id_number'=>$cus[0]['id_number'],
        'level'=>$lv,
        'gender'=>$cus[0]['gender']==1?"男":"女",
        'cash'=>$cus[0]['cash']/100
        
    ]);
}else{
    echo json_encode(['status'=>0]);
}
?>