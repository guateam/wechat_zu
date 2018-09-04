<?php
require 'database.php';
require 'gettechnician.php';
require 'getfeedback.php';

$id=$_POST['id'];
echo(json_encode(['status'=>1,'data'=>['technician'=>gettechnician($id),'feedback'=>getfeedback($id)]]));