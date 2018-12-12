<?php
require "database.php";
require "getdiscount.php";
require "getservicetype.php";
require "getnotice.php";
require "getshop.php";
$id = $_POST['id'];
$now_time = time();

$pic = sql_str("select A.dir AS url,B.dir AS dir from shop_photo A, service_type B where A.shop_id = 1 and (B.ID = A.theme)");
$npic = sql_str("select A.dir AS url from shop_photo A where A.shop_id = 1 and 0= A.theme");
for($i=0;$i<count($npic);$i++){
    $npic[$i] = array_merge($npic[$i],['dir'=>'qiyewenhua.html']);
}
//获取最近的5条优惠活动
$promo = sql_str("select *, abs($now_time - start) as leap from promotion order by leap ASC limit 5");

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

//找出属于某类的项目，并且删除app2中同类的项目，只留下一个
foreach($app2['data'] as $key => $it){
    $res = false;
    preg_match($reg1,$it['name'],$res);
    if($res){
        array_push($foot,$it);
        unset($app2['data'][$key]);
    }else{
        preg_match($reg2,$it['name'],$res);
        if($res){
            array_push($spa,$it);
            unset($app2['data'][$key]);
        }
    }
}
$app2['data'] = array_values($app2['data']);

echo (json_encode(['status' => 1,'top_pic'=>$pic,'data'=>[
                            'tab1'=>$tab1,
                            'tab2'=>$tab2,
                            'foot'=>$foot,
                            'spa'=>$spa,
                            'app1' => getdiscount($id),
                            'app2' => $app2,
                            'shop' => getshop($id),
                            'notice' => getnotice($id),
                            'promo'=>$promo]
                ]));
