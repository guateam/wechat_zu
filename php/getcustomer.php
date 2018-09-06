<?php
require("databse.php");
$phone = $_POST['phone'];
$cus = get("customer","phone_number",$phone);
if($cus){
    echo json_encode([
        'name'=>$cus['name'],
        'head'=>$cus['head'],
        'phone_number'=>$cus['phone_number'],
        'id_number'=>$cus['id_number'],
        'level'=>$cus['level'],
        'gender'=>$cus['gender']==1?"男":"女",
        
    ]);
}
?>