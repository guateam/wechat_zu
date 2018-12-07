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
//显示在小程序上面的两个标签内容
$tab1 ="足浴三选一";
$tab2 ="SPA三选一";
//筛选用的正则表达式
$reg1='/足浴/';
$reg2='/spa|SPA/';
//属于某一类的服务
$foot = [];
$spa = [];

foreach($app2['data'] as $it){
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

echo (json_encode(['status' => 1,'top_pic'=>$pic,'data'=>['tab1'=>$tab1,'tab2'=>$tab2,'foot'=>$foot,'spa'=>$spa,'app1' => getdiscount($id), 'app2' => $app2, 'shop' => getshop($id), 'notice' => getnotice($id)]]));
