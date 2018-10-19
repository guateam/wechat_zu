<?php
if(isset($_POST['ajax_get']))
    require("database.php");
function get_promotion()
{
    $time = time();
    $dates = get("promotion");
    $result = [];
    foreach($dates as $date)
	{
        $start = strtotime($date['start']);
        $end = strtotime($date['end']);
        if($start <= $time && $time <= $end)
		{
            array_push($result,$date);
        }
    }
    return $result;
}
//若不是ajax请求，不执行语句，用于给其他php文件require的时候不额外执行内容
if(isset($_POST['ajax_get']))
{
    echo json_encode(['status'=>1,'data'=>get_promotion()]);
}