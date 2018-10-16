<?php
require 'database.php';
require 'gettechnician.php';
require 'getfeedback.php';

$id=$_POST['id'];
$tm = $_POST['time'];
$count = count(get("service_order","job_number",$id));
echo(json_encode(['status'=>1,'data'=>['order_num'=>$count,'technician'=>gettechnician($id,$tm),'feedback'=>getfeedback($id)]]));