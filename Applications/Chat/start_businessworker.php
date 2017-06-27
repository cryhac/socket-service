<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
use \Workerman\Worker;
use \GatewayWorker\BusinessWorker;
use \App\Services\MessageService;

// bussinessWorker 进程
$worker = new BusinessWorker();
// worker名称
$worker->name = 'ChatBusinessWorker';
// bussinessWorker进程数量
$worker->count = 4;
// 服务注册地址
$worker->registerAddress = '127.0.0.1:1236';

$worker->onWorkerStart = function ($worker) {
    if ($worker->id === 0) {
        //订阅频道
        MessageService::addPushMessageTimer(['channel' => 'channel1']);
        MessageService::addPushMessageTimer(['channel' => 'channel2']);
        //广播频道
        MessageService::addPushMessageTimer(['channel' => 'broadcast']);
    }
};
// 运行worker

// 如果不是在根目录启动，则运行runAll方法
if (!defined('GLOBAL_START')) {
    Worker::runAll();
}

