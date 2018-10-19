<?php
require("database.php");
$user_id = $_POST['user_id'];
$is_vip = get("customer",'ID',$user_id);
$head = $is_vip[0]['head'];
$is_vip = $is_vip[0]['level']==0?false:true;

$str = "select `recharge_record`.`user_id` AS `user_id`,sum(`recharge_record`.`charge`) AS `total_charge` from `recharge_record` group by `recharge_record`.`user_id`";
$result = sql_str($str);
$data=[];
foreach($result as $row)
{
    if($row['user_id'] == $user_id)
	{
        $result = $row;
        $str = "SELECT `extra_duration` FROM vip_information WHERE necessary_charge_to_level_up > ".$result['total_charge']." LIMIT 1";
        $extradura = (sql_str($str))[0]['extra_duration'];

        $str = "SELECT group_concat(`necessary_charge_to_level_up` SEPARATOR ',')  AS str FROM vip_information WHERE necessary_charge_to_level_up > ".$result['total_charge']." LIMIT 1 ;";
        $exp = (int)(explode(',',(sql_str($str))[0]['str']))[0];
        if($exp)$exp = $exp -  $result['total_charge'];
        else $exp = 0;

        $str = "SELECT group_concat(`name` SEPARATOR ',') AS str FROM vip_information WHERE necessary_charge_to_level_up > ".$result['total_charge'];
        $lv_list = (explode(',',(sql_str($str))[0]['str']));
        $nowlv = $lv_list[0];
        $nxlv = "无";
        if(count($lv_list)>1) $nxlv = $lv_list[1];

        $str = "SELECT `discount_ratio` FROM vip_information WHERE necessary_charge_to_level_up > ".$result['total_charge']." LIMIT 1 ";
        $discount = (sql_str($str))[0]['discount_ratio'];

        $result = array_merge($result,["extradura"=>$extradura,"exp"=>$exp,'nowlv'=>$nowlv,'nxlv'=>$nxlv,'discount'=>$discount]);
        break;
    }
}
    
if($result && $is_vip)
{ 
    $result['exp']/=100;
    $result['total_charge']/=100;
    echo json_encode(['status'=>1,'data'=>$result,'head'=>$head]);
}
else
{
    $first_lv = get("vip_information",'level',1);
    echo json_encode(['status'=>0,'head'=>$head,'data'=>[
        'next_lv'=>$first_lv[0]['name'],
    ]]);
}