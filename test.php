<?php
require_once __DIR__ . '/bootstrap/app.php';

//发送广播消息
$data = [
    'uid' => 0,//广播消息
    'channel' => 'broadcast',
    'body' => json_encode(['info' => 'this is a test broadcast!!!']),
];
$socket = \App\Services\SocketService::set($data);


//发送消息给频道:channel1 .uid=5的用户.
$data = [
    'uid' => 5,
    'channel' => 'channel1',
    'body' => json_encode(['info' => 'this is a test channel1 uid=5 message!!!']),
];
//\App\Services\LogService::error("这个是error消息", $data);
//\App\Services\LogService::info("这个是info消息", $data);
$socket = \App\Services\SocketService::set($data);