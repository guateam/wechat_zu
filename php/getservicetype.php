<?php
function getservicetype($id)
{
    $servicetype=get('service_type');
    $data=[];
    foreach($servicetype as $value)
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
    return ['status'=>1,'data'=>$data];
}
?>