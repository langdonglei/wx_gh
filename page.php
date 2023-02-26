<?php
$this_page  = 'http://cqaxym.natappfree.cc/page.php';
$app_id     = "wxa1c7ede1622f604e";
$app_secret = "543ad7b88c9206042476ebef0da3809e";

if (empty($_GET['code'])) {
    $redirect_uri = urlEncode($this_page);
    $url          = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$app_id&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
    Header("Location: $url");
}
$code = $_GET['code'];

$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$app_id&secret=$app_secret&code=$code&grant_type=authorization_code";
$str = file_get_contents($url);
$arr = json_decode($str, true);

echo file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=$arr[access_token]&openid=$arr[openid]&lang=zh_CN");




