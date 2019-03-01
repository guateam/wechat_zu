<?php
require("database.php");
$order_id = $_POST['order_id'];
// sql_str("delete from service_order where order_id='$order_id'");
// sql_str("delete from consumed_order where order_id='$order_id'");

sql_str("delete from service_order where order_id='$order_id' and order_id in (select order_id from consumed_order where (state = 3 or state = 0))");

sql_str("delete from consumed_order where (order_id='$order_id' and state = 3 or state = 0");

echo json_encode(['status'=>1]);

?>