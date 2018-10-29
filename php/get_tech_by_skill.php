<?php
require('database.php');
$id =$_POST['service_id'];
$numbers = [];
//获取服务
$service_name = sql_str("select name,have_level from service_type where `ID` = '$id'");
$tech_info = sql_str("select A.job_number,A.photo,B.level,C.name from technician A,skill B,skill_level C where A.job_number = B.job_number and B.service_id = '$id' and C.ID = B.level");
$no_level = sql_str("select A.job_number,A.photo,B.level from technician A,skill B where A.job_number = B.job_number and B.service_id = '$id' and B.level = 0");
for($i=0;$i<count($no_level);$i++){
    $no_level[$i] =  array_merge($no_level[$i],['name'=>""]);
}
$tech_info = array_merge($tech_info,$no_level);
// $friend_circle = sql_str("select ")
foreach($tech_info as $idx => $tc){
    $job_number = $tc['job_number'];
    $friend_circle = sql_str("select A.img from technician_photo A where A.job_number = '$job_number' order by A.ID desc limit 3");
    $rate = sql_str("select Avg(score) as score from rate where job_number = '$job_number'");
    //保留一位小数
    $rate = round($rate[0]['score'],1);
    $tech_info[$idx] = array_merge($tech_info[$idx],['img_list'=>$friend_circle,'rate'=>$rate]);
    if($service_name[0]['have_level'] == 0)
        $tech_info[$idx]['level'] = "";
}
$svnm = $service_name[0]['name'];
echo json_encode(['status'=>1,'data'=>$tech_info,'service_name'=>$svnm,'have_level'=>$service_name[0]['have_level'] ]);