<?php
function getservicetype($id){
    $servicetype=get('service_type');
    $data=[];
    foreach($servicetype as $value){
        $item=[
            'name'=>$value['name'],
            'time'=>$value['duration'],
            'price'=>$value['price']/100.0
        ];
        array_push($data,$item);
    }
    return ['status'=>1,'data'=>$data];
}