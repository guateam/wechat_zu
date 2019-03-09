<?php
require("database.php");

$openid = $_POST['openid'];//客户的openid
$rate = $_POST['rate'];//打分
$job_number = $_POST['job_number'];//技师工号
$comment = $_POST['comment'];//评论
$tags = [];//评价的标签
if(isset($_POST['tags']))
{
	$tags = $_POST['tags'];
}

$user = get('customer','openid',$openid);//查询到客户
if($user)
{
    
	$jbnb = $job_number[0];
	$arr_jbnb = ['job_number',$jbnb];//工号

	$rt = $rate[0];
	$arr_rt = ['score',$rt];//打分

	$cmt = $comment[0];
	$arr_cmt = ['comment',$cmt];//评价

	$cus_id = $user[0]['openid'];
	$arr_cusid = ['customer_id',$cus_id];//客户openid

	$arr_time = ['time',time()];//时间

	foreach($tags as $tag)
	{
		//如果目前遍历的服务下标和tag标记的服务下标一样，添加tag
		if($tag['jbnb'] == $job_number[0])
		{
			add('tech_tag',[$arr_jbnb,['tag_id',$tag['id'] ]]);//对某个技师打多个标签
		}
	}

	add('rate',[$arr_jbnb, $arr_rt, $arr_cmt,  $arr_cusid, $arr_time]);//评价记录添加到rate表
   
    echo(json_encode(['status'=>1]));
}
else
{
    echo(json_encode(['status'=>0]));
}
?>