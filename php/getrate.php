<?php
require("database.php");
$order_id = $_POST['id'];
$str = "SELECT
`rate`.`order_id` AS `order_id`,
`rate`.`score` AS `score`,
`rate`.`comment` AS `comment`,
`rate`.`bad` AS `bad`,
`service_type`.`name` AS `name`,
`service_order`.`job_number` AS `job_number`,
`consumed_order`.`generated_time` AS `generated_time`,
`rate`.`customer_id` AS `customer_id` 
FROM
(
    (
        ( `rate` JOIN `service_type` ON ( ( `rate`.`service_id` = `service_type`.`ID` ) ) )
        JOIN `service_order` ON ( ( `service_order`.`order_id` = `rate`.`order_id` ) ) 
    )
JOIN `consumed_order` ON ( ( `consumed_order`.`order_id` = `rate`.`order_id` ) ) 
)";

$rates = sql_str($str);
$this_rate = [];
foreach($rates as $rate) {
 if($rate['order_id'] == $order_id){
     array_push($this_rate,$rate);
 }
}

if($rate){
    echo json_encode(['status'=>1,'data'=>$rate]);
}else{
    echo json_encode(['status'=>0]);
}
