<?php
require 'database.php';
require 'gettechnician.php';
require 'getfeedback.php';

$id=$_POST['id'];
$tm = $_POST['time'];
echo(json_encode(['status'=>1,'data'=>['technician'=>gettechnician($id,$tm),'feedback'=>getfeedback($id)]]));