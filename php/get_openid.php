<?php
//声明CODE，获取小程序传过来的CODE
$code = $_POST["code"];
//配置appid
$appid = "wxe1e434222057b10e";
//配置appscret
$secret = "c5283cabffbbe714ba1c333fcead2487";
//api接口
$api = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";
//获取GET请求
function httpGet($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

    //根据不同版本使用curl_setopt的第三个参数
    $value2 = true;
    if(version_compare(PHP_VERSION,'7.28.1', '<'))$value2 = 2;
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $value2);

    
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}
//发送
$str = httpGet($api);
echo $str;
?>