<?php
require('database.php');
date_default_timezone_set('PRC');
//获取店铺信息
$shop="御足堂影院式足道";
if(isset( $_COOKIE['zys'])){
    $shop_name = $_COOKIE['zys'];
}
$shop = get("shop","name",$shop);
if($shop){
    $result = [];
    //获取开业时间，结业时间，当前时间, 是否允许预约
    array_push($result,
    ['open'=>$shop[0]['open_time'],
    'close'=>$shop[0]['close_time'],
    'today'=>strtotime(date("Y-m-d",time())),
    'pre'=>$shop[0]['appoint_allow']
    ]);
    echo json_encode(['status'=>1,"data"=>$result]);
}else{
    echo json_encode(['status'=>0]);
}