<?php
require("database.php");
$openid = $_POST['openid'];
$username = $_POST['username'];
$gender = $_POST['gender'];
$head = $_POST['head'];

sql_str("insert into customer (gender,name,head,openid) values ('$gender','$username','$head','$openid')");
echo json_encode(['status'=>1]);
?>