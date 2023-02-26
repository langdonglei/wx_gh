<?php

include_once 'vendor/autoload.php';

function sign()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce     = $_GET['nonce'];
        $token     = '123456';
        $arr       = [$token, $timestamp, $nonce];
        sort($arr, SORT_STRING);
        $str = implode($arr);
        $str = sha1($str);
        if ($str != $signature) {
            throw new Exception('signature error');
        } else {
            echo $_GET['echostr'];
            exit();
        }
    }
}

function msg($from, $to, $type, $item): string
{
    switch ($type) {
        case 'text':
            return <<<EOF
<xml>
    <CreateTime></CreateTime>
    <ToUserName><![CDATA[$to]]></ToUserName>
    <FromUserName><![CDATA[$from]]></FromUserName>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[$item]]></Content>
</xml>
EOF;
        case 'image':
            return <<<EOF
<xml>
    <CreateTime></CreateTime>
    <ToUserName><![CDATA[$to]]></ToUserName>
    <FromUserName><![CDATA[$from]]></FromUserName>
    <MsgType><![CDATA[image]]></MsgType>
    <Image>
        <MediaId><![CDATA[$item]]></MediaId>
    </Image>
</xml>
EOF;
        default:
            throw new Exception('type error');
    }
}


try {
    sign();
    $xml = file_get_contents('php://input');
    $dom = simplexml_load_string($xml);
    switch ($dom->MsgType) {
        case 'text';
            echo msg($dom->ToUserName, $dom->FromUserName, 'text', $dom->Content);
            break;
        case 'image';
            echo msg($dom->ToUserName, $dom->FromUserName, 'image', $dom->MediaId);
            break;
        default:
            throw new Exception('type error');
    }
} catch (Throwable $e) {
    $err = $e->getMessage();
} finally {
    file_put_contents('index.log', date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
    file_put_contents('index.log', ($xml ?? '') . PHP_EOL, FILE_APPEND);
    file_put_contents('index.log', ($msg ?? '') . PHP_EOL, FILE_APPEND);
    file_put_contents('index.log', ($err ?? '') . PHP_EOL, FILE_APPEND);
}

