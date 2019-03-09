<?php
require("database.php");

$openid = $_POST['openid'];//客户的openid
$id = $_POST['order_id'];//订单号
$service_id = $_POST['service_id'];//服务项目
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
    foreach($service_id as $idx => $svid)
	{
        $jbnb = $job_number[$idx];
        $arr_jbnb = ['job_number',$jbnb];//工号

        $rt = $rate[$idx];
        $arr_rt = ['score',$rt];//打分

        $cmt = $comment[$idx];
        $arr_cmt = ['comment',$cmt];//评价

        $cus_id = $user[0]['openid'];
        $arr_cusid = ['customer_id',$cus_id];//客户openid

        $arr_id = ['order_id',$id];//订单id

        $arr_svid = ['service_id',$svid];//服务项目id

        $arr_time = ['time',time()];//时间

        foreach($tags as $tag)
		{
            //如果目前遍历的服务下标和tag标记的服务下标一样，添加tag
            if($tag['jbnb'] == $job_number[$idx] && $tag['svid'] == $svid )
			{
				add('tech_tag',[$arr_id,$arr_jbnb,['tag_id',$tag['id'] ]]);//对某个技师打多个标签
			}
        }

        add('rate',[$arr_id, $arr_jbnb, $arr_rt, $arr_cmt, $arr_svid, $arr_cusid, $arr_time]);//评价记录添加到rate表
    }
    set('consumed_order','order_id',$id,[['state',5]]);//订单状态设置为5 评价完成
    echo(json_encode(['status'=>1]));
}
else
{
    echo(json_encode(['status'=>0]));
}
?>