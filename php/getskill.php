<?php
require("database.php");
//获取预约信息: 工号和对应的服务
$sv = $_COOKIE['fn'];
$phone = (get("shop"))[0]['phone'];
$sv = explode(',',$sv);
$info = [];
$total_time = 0;
$total_price = 0;
for($i=0;$i<count($sv);$i++){
    $temp = explode('-',$sv[$i]);  
    $job_number = explode(':',$temp[0]);
    $job_number = $job_number[1];
    $id = explode(':',$temp[1]);
    $id = $id[1];
    $service = sql_str("select * from service_type where `ID`='$id'");
    $total_time+=intval($service[0]['duration'])*60;
    $total_price+=intval($service[0]['price']);
    $tech = sql_str("select job_number,photo from technician where `job_number`='$job_number'");
    array_push($info,['tech'=>$tech[0],'service'=>$service[0]]);
}
echo json_encode(['status'=>1,'data'=>$info,'phone'=>$phone,'exist'=>$total_time,'total_price'=>$total_price]);