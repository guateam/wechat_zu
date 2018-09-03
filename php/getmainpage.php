<?php
require "database.php";
require "getdiscount.php";
require "getservicetype.php";
require "getnotice.php";
require "getshop.php";
$id = $_POST['id'];
echo (json_encode(['status' => 1,'data'=>['app1' => getdiscount($id), 'app2' => getservicetype($id), 'shop' => getshop($id), 'notice' => getnotice($id)]]));
