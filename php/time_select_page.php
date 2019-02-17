<?php
require('database.php');
date_default_timezone_set('PRC');
//获取店铺信息
//$shop="御足堂影院式足道";
if(isset( $_COOKIE['zys'])){
    $shop_name = $_COOKIE['zys'];
}
//默认没有选中技师
$job_number = null;
$appoint_time = [];
//获取选中的技师
if(isset($_POST['jobnumber'])){
    $job_number =$_POST['jobnumber'];
    $now = time();
    //已经被预约的时间段
    $appoint_time = sql_str("select A.appoint_time as `begin`, A.appoint_time+C.duration*60 as `end` from consumed_order A,service_order B,service_type C where A.appoint_time >= $now and B.order_id = A.order_id and B.job_number = '$job_number' and C.ID=B.item_id");
}

$shop = sql_str("select * from shop limit 1");// get("shop","name",$shop);
if($shop){
    $result = [];

    $open = strtotime(date("Y-m-d ",time()).$shop[0]['open_time']);//开业时间戳
    $close = strtotime(date("Y-m-d ",time()).$shop[0]['close_time']);;//结业时间戳
    $today = strtotime(date("Y-m-d",time()));//当天0点时间戳
    $leap = 1800;//时间块间隔多少秒
    $row = [];//一行时间块
    $time_block = []; //营业时间块
    $now = time();//目前的时间戳
    //填充时间块变量
    for($i=$open,$j=0;$i<=$close;$i+=$leap,$j++){
        //如果目前时间比时间块时间大25分钟，则时间块无法选择(时间块里面最多细分到超前25分钟)
        $can = 1;
        if($now>=$i+(60*25))$can = 0;
        //如果时间块已经有预约，则无法选择
        for($k =0;$k<count($appoint_time);$k++){
            if($i >= $appoint_time[$k]['begin'] && $i <=  $appoint_time[$k]['end']){
                $can = 0;
            }
        }
        array_push($row,['time'=>$i,'choosen'=>0,'allow'=>$can]);
        if (($j + 1) % 4 == 0) {
            array_push($time_block,$row);
            $row = [];
        } 
        // else {
        //     array_push($row,['time'=>$i,'choosen'=>0,'allow'=>(time()>$i+(60*25)?0:1)]);
        // }
    }
    //将多余的最后一行push进去
    array_push($time_block,$row);
    //第二天和第三天的时间块
    $time_block_2 = $time_block;
    $time_block_3 = $time_block;
    //重新确认第二天和第三天的预约情况
    for($i=0;$i<count($time_block_2);$i++){
        for($j=0;$j<count($time_block_2[$i]);$j++){
            $time_block_2[$i][$j]['allow'] = 1;
            $time_block_2[$i][$j]['time']+=24*60*60;

            $time_block_3[$i][$j]['allow'] = 1;
            $time_block_3[$i][$j]['time']+=2*24*60*60;
            
            //如果时间块已经有预约，则无法选择
            for($k =0;$k<count($appoint_time);$k++){
                if($time_block_2[$i][$j]['time'] >= $appoint_time[$k]['begin'] && $time_block_2[$i][$j]['time'] <=  $appoint_time[$k]['end']){
                    $time_block_2[$i][$j]['allow'] = 0;
                }
    
                if($time_block_3[$i][$j]['time'] >= $appoint_time[$k]['begin'] && $time_block_3[$i][$j]['time'] <=  $appoint_time[$k]['end']){
                    $time_block_3[$i][$j]['allow'] = 0;
                }
            }
        }
    }

    //获取开业时间，结业时间，当前时间, 是否允许预约
    array_push($result,
    ['open'=>$shop[0]['open_time'],
    'close'=>$shop[0]['close_time'],
    'today'=>strtotime(date("Y-m-d",time())),
    'time_block'=>$time_block,
    'time_block_2'=>$time_block_2,
    'time_block_3'=>$time_block_3,
    'pre'=>$shop[0]['appoint_allow']
    ]);
    echo json_encode(['status'=>1,"data"=>$result]);
}else{
    echo json_encode(['status'=>0]);
}