<?php
require "database.php";
require "getdiscount.php";
require "getservicetype.php";
require "getnotice.php";
require "getshop.php";
$id = $_POST['id'];
$pic = sql_str("select A.dir AS url,B.dir AS dir from shop_photo A, service_type B where A.shop_id = 1 and (B.ID = A.theme)");
$npic = sql_str("select A.dir AS url from shop_photo A where A.shop_id = 1 and 0= A.theme");
for($i=0;$i<count($npic);$i++){
    $npic[$i] = array_merge($npic[$i],['dir'=>'qiyewenhua.html']);
}
$pic = array_merge($pic,$npic);
$app2 = getservicetype($id);
$reg1='/足浴/';
$reg2='/spa|SPA/';
$foot = [];
$spa = [];
foreach($app2 as $it){
    $res = false;
    preg_match($reg1,$it['name'],$res);
    if($res){
        array_push($foot,$it);
    }else{
        preg_match($reg2,$it['name'],$res);
        if($res){
            array_push($spa,$it);
        }
    }
}

echo (json_encode(['status' => 1,'top_pic'=>$pic,'data'=>['foot'=>$foot,'spa'=>$spa,'app1' => getdiscount($id), 'app2' => $app2, 'shop' => getshop($id), 'notice' => getnotice($id)]]));
