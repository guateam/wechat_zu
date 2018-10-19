<?php
require("database.php");

$openid=$_POST['openid'];
$id=$_POST['id'];
$rate=$_POST['rate'];
$comment=$_POST['comment'];

$user=get('customer','openid',$openid);
if($user)
{
    $user=$user[0];
    add('rate',[['order_id',$id],['score',$rate],['comment',$comment]]);
    echo(json_encode(['status'=>1]));
}
else
{
    echo(json_encode(['status'=>0]));
}