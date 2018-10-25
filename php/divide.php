<?php
$block = $_POST['block'];
$idx = $_POST['idx'];
$idx2 = $_POST['idx2'];
$time_block_length = $_POST['length'];
$day = $_POST['day'];
$leap=1800;
$start = strtotime(date("Y-m-d ",$day).$block['time']);
//新的divide_line
$tp = [];
$divide_block = [];

for ($i = $start; $i < $start + $leap; $i += 300) {
    $si = date("Y-m-d H:i:s",$i);

    $p = $i+$day-(3600*24);
    array_push($divide_block,['time'=>date("H:i", $i),'choosen'=>0,'allow'=>(time()>$i?0:1)]);
}
for ($i = 0; $i < $time_block_length; $i++) {
    array_push($tp,$idx == $i ? 1 : 0);
}
echo json_encode(['block'=>$divide_block,'tp'=>$tp]);