<?php
require("database.php");
$enter = $_POST['enter'];
$tea_tech = $_POST['tc'];

$tea = sql_str("select * from item_type");
$data = [];
//1表示选择过技师，0表示未选择过技师
if($enter == 1){
    for($i=0;$i<count($tea_tech);$i++){
        $tea_tech[$i] = ['job_number'=>$tea_tech[$i]];
        $tea_tech[$i] = array_merge($tea_tech[$i],['tea'=>$tea,'choosen'=>$i==0?true:false]);
        $data = array_merge($data,[$tea_tech[$i]['job_number']=>$tea_tech[$i] ]);
    }
    echo json_encode(['status'=>1,'tea'=>$tea,'tea_tech'=>$data]);
    exit();
}else{
    $data = array_merge($data,['all'=>['job_number'=>"所有茶品",'choosen'=>true,'tea'=>$tea] ]);
    echo json_encode(['status'=>1,'tea'=>$tea,'tea_tech'=>$data]);
    exit();
}

