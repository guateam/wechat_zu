<?php
require("database.php");

$openid=$_POST['openid'];
$id=$_POST['order_id'];
$service_id = $_POST['service_id'];
$rate=$_POST['rate'];
$comment=$_POST['comment'];

$user=get('customer','openid',$openid);
if($user)
{
    $user=$user[0];
    foreach($service_id as $idx => $svid){
        add('rate',[['order_id',$id],['score',$rate[$idx]],['comment',$comment[$idx]],['service_id',$svid],['customer_id',$user[0]['ID']]]);
    }
    set('consumed_order','order_id',$id,[['state',5]]);
    echo(json_encode(['status'=>1]));
}
else
{
    echo(json_encode(['status'=>0]));
}