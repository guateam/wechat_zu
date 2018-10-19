<?php
require("database.php");
$consumed_order = get("consumed_order");
$info_rev = [];
$info = [];
foreach($consumed_order as $co)
{
    $so = get("service_order","order_id",$co['order_id']);
    foreach($so as $svod)
	{
        if($svod['show'] != 1)
		{
            continue;
        }
        $tech = get("technician","job_number",$svod['job_number']);
        $svs = get("service_type","ID",$svod['item_id']);
        if($tech && $svs)
		{
            array_push($info,[
                "ID"=>$svod['ID'],
                "job_number"=>$tech[0]['job_number'],
                "price"=>$svs[0]['price']*$svs[0]['discount']*0.01*0.01,
                "order_id"=>$co['order_id'],
                "state"=>$co['state'],
                "time"=>substr($co['generated_time'],0,10)
            ]);
        }
		else
		{
            array_push($info,[
                "ID"=>"",
                "technician_name"=>"",
                "job_number"=>"",
                "price"=>0,
                "order_id"=>"",
                "state"=>"",
                "time"=>"",
            ]);
        }
    }
}
for($i = count($info)-1;$i>=0;$i--)
{
    array_push($info_rev,$info[$i]);
}
echo json_encode($info_rev);
