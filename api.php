<?php
use GuzzleHttp\Client;
use GuzzleHttp\Middleware;

include_once 'vendor/autoload.php';

function url($path): string
{
    $app_id       = 'wxa1c7ede1622f604e';
    $app_secret   = '543ad7b88c9206042476ebef0da3809e';
    $api_token    = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$app_id&secret=$app_secret";
    $str          = file_get_contents($api_token);
    $access_token = json_decode($str, true)['access_token'];
    return $path . '?access_token=' . $access_token;
}

function create_menu($client)
{
    $clientHandler = $client->getConfig('handler');
    $tapMiddleware = Middleware::tap(function ($request) {
        echo $request->getHeader('Content-Type');
        echo $request->getBody();
    });

    echo $client->request('post', url('/cgi-bin/menu/create'), [
            'body'   => json_encode([
                'button' => [
                    [
                        'name' => '克里克',
                        'type' => 'click',
                        'key'  => 'a'
                    ],
                    [
                        'name' => '网页',
                        'type' => 'view',
                        'url'  => 'http://cqaxym.natappfree.cc/a.php'
                    ]
                ]
            ], JSON_UNESCAPED_UNICODE),
            'handle' => $tapMiddleware($clientHandler),
        ])->getBody()->getContents() . PHP_EOL;
}

$client = new Client([
    'base_uri' => 'https://api.weixin.qq.com',
    //    'debug' => true,
]);

create_menu($client);





