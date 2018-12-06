<?php
require("database.php");

$tcs = '';
$time = time();
if(isset($_POST['tc']))$tcs = $_POST['tc'];
if(isset($_POST['time']))$time = $_POST['time'];
//选择了技师的情况
if($tcs != '' && $tcs[0] != '')
{
    $skills = sql_str("SELECT
    `skill`.`level` AS `level`,
    `skill`.`job_number` AS `job_number`,
    `service_type`.`name` AS `name`,
    `service_type`.`image` AS `image`,
    `service_type`.`duration` AS `duration`,
    `service_type`.`price` AS `price`,
    `service_type`.`ID` AS `id`
    FROM
    ( `skill` JOIN `service_type` ) 
    WHERE
    ( `service_type`.`ID` = `skill`.`service_id` )
    ORDER BY
    `skill`.`job_number`");
    
	$list = new stdClass();//输出的技师-服务列表
    
	for($i=0;$i<count($tcs);$i++)
	{
        //默认选择第一个技师
        $jbnb = $tcs[$i];
        //判断目前是否是忙碌状态
        $appoint_time = sql_str("select A.appoint_time as `begin`, A.appoint_time+C.duration*60 as `end` from consumed_order A,service_order B,service_type C where A.appoint_time >= $now and B.order_id = A.order_id and B.job_number = '$job_number' and C.ID=B.item_id");
        foreach($appoint_time as $app_time){
            if($time>=$app_time['begin'] && $time <= $app_time['end']){
                echo json_encode(['status'=>-1]);
                exit();
            }
        }
        
        $list->$jbnb =['skill'=>[],'choosen'=>$i==0?true:false,'job_number'=>$tcs[$i]];
    }
	$out_list = new stdClass();
    foreach($skills as $skill)
	{
        //单位换算  分->元
        $skill['price']/=100;
        //添加服务的选择状态
        $skill = array_merge($skill,['choosen'=>false]);
        //若该技师在传入的技师列表中
        if(in_array($skill['job_number'],$tcs)){
            //在输出的技师-服务列表中添加该技师的服务信息
            $jbnb = $skill['job_number'];
            //$list->$jbnb['skill'] = 0;//array_merge(($list->$jbnb)['skill'],[$skill]);
			$test = $list->$jbnb;
			$test['skill'] = array_merge($test['skill'],[$skill]);
			$list->$jbnb = $test;
        }
    }
    echo json_encode(['status'=>1,'data'=>(array)$list]);
	
	//echo "1";
}
else //未选择技师的情况
{
    $skills = sql_str("SELECT
    `service_type`.`name` AS `name`,
    `service_type`.`image` AS `image`,
    `service_type`.`duration` AS `duration`,
    `service_type`.`price` AS `price` ,
    `service_type`.`ID` AS `id`
    FROM
    (`service_type` )");
     $list = [];//输出的技师-服务列表
     $list = array_merge($list,['all'=>['skill'=>[],'choosen'=>true,'job_number'=>"所有"]]);
     foreach($skills as $skill){
        //单位换算  分->元
        $skill['price']/=100;
        //添加服务的选择状态
        $skill = array_merge($skill,['choosen'=>false]);
        //在输出的技师-服务列表中添加该技师的服务信息
        $list['all']['skill'] = array_merge($list['all']['skill'],[$skill]);
    }
    echo json_encode(['status'=>1,'data'=>$list]);
}
class A {
   public $idx;
   public $item;
   public function __construct($i,$item) {
     $this->i = $i;
	 $this->item = $item;
   }
 }
?>
