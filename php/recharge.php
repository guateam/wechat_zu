<?php
require("database.php");
$user = $_POST['user_id'];
$us = get("customer","ID",$user);

if($us){
    $val = $_POST['charge'];
    $job_number = null;
    if(!isset($_POST['job_number']))$job_number = $_POST['job_number'];
    $pay = $_POST['pay'];
    $dict=['1','2','3','4','5','6','7','8','9','0'];
    $rnd = "";
    $rnd.=date("YmdHis");
    for($i=0;$i<2;$i++){
        $rnd.=$dict[rand(0,count($dict)-1)];
    }
    for($i=0;$i<4;$i++){
        $rnd.=$dict[rand(0,count($dict)-1)];
    }
    //这里添加扣钱的代码
    
    
    
    
    //
    add("recharge_record",[
        ["record_id",$rnd],
        ["user_id",$user],
        ['charge',$val*100],
        ['payment_method',$pay],
        ['job_number',$job_number],
        ]);
    $new_user = get('recharge_info','user_id',$user);
    $new_level = get("vip_information",'name',$new_user[0]['lv']);
    set("customer","ID",$user,[['cash',$us[0]['cash']+$val*100],['level',$new_level[0]['level']]]);


    echo json_encode(['status'=>1]);
}else{
    echo json_encode(['status'=>0]);
}
