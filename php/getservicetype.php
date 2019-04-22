<?php
function getservicetype($id)
{
    $servicetype=get('service_type');
    $data=[];
    foreach($servicetype as $value)
	{
		if((strpos($value['name'],'spa') === false) && (strpos($value['name'],'SPA') === false) && (strpos($value['name'],'SAP') === false) &&  (strpos($value['name'],'中式推拿') === false))
		{
			$item=[
				'id'=>$value['ID'],
				'show'=>$value['index_show'],
				'procedure'=>$value['procedure'],
				'attention'=>$value['attention'],
				'benefit'=>$value['benefit'],
				'name'=>$value['name'],
				'time'=>$value['duration'],
				'price'=>$value['price']/100.0,
				'img'=>$value['image'],
				'belong_to'=>$value['belong_to'],
				'market_price'=>$value['market_price']/100.0
			];
			array_push($data,$item);
		}
    }
    return ['status'=>1,'data'=>$data];
}
?>