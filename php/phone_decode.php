<?php
require("database.php");
include_once "wxBizDataCrypt.php";


$appid = $_POST['appid'];
$sessionKey = $_POST['session_key'];
$encryptedData=$_POST['encryptedData'];
$iv = $_POST['iv'];
$openid = $_POST['openid'];

$pc = new WXBizDataCrypt($appid, $sessionKey);
$errCode = $pc->decryptData($encryptedData, $iv, $data );

if ($errCode == 0) {
    $data = (array)(json_decode($data));
    $phone = $data['phoneNumber'];
    $result = sql_str("update customer set `phone_number` = '$phone' where `openid`='$openid'");
    if($result)
        echo json_encode(['status'=>1,'phone'=>$phone]);
    else
        echo json_encode(['status'=>-1]);
} else {
    echo json_encode(['status'=>0]);
}
