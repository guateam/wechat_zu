<?php
function getshop($id){
    $shop=get('shop');
    return ['status'=>1,'data'=>['name'=>$shop[0]['name'],'position'=>$shop[0]['position'],'location'=>'../html/map.html?location='.$shop[0]['position']]];
}