<?php
require("database.php");
$jobnumber = $_POST['job_number'];

$tech = get("technician","job_number",$jobnumber);

$info = [];

if ($tech)
{
	array_push($info,[
			"technician_name"=>$tech[0]['name'],
			"technician_head"=>$tech[0]['photo'],
			"nickname"=>$tech[0]['nickname'],
			"job_number"=>$tech[0]['job_number'],
	]);
}


//------------获取技师评价的tag--------------------
$tag = sql_str("select * from rate_tag");
if($tag)
{
    for($i=0;$i<count($tag);$i++)
	{
        $tag[$i] = array_merge($tag[$i],['check'=>false]);
    }
}

$tags = [];
array_push($tags,$tag);

//--------------------------------------------------
echo json_encode(['data'=>$info,'co'=>'','tags'=>$tags]);
?>