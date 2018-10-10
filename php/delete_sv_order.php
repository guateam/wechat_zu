<?php
require("database.php");
$id = $_POST['id'];
$svod = set("service_order","ID",$id,[["show",0]]);
echo json_encode(['status'=>1]);

