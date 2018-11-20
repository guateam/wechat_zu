<?php
require("database.php");
$openid = $_POST['openid'];
$cus = sql_str("select * from customer where `openid`='$openid'");
if($cus){
    echo  json_encode(['status'=>1]);
}
else {
    echo json_encode(['status'=>0]);
}