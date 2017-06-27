<?php
/**
 * Created by PhpStorm.
 * User: will
 * Date: 2017/6/27
 * Time: 上午10:33
 */

namespace App\Channels;

use App\Services\LogService;
use App\Services\SocketService;
use GatewayWorker\Lib\Gateway;

class BroadcastMessage implements ChannelMessageInterface
{
    public function handle($data = [])
    {
        $channel = array_get($data, "channel");
        $message = SocketService::getChannelMessage($channel, SocketService::BROAD_CAST_UID);
        LogService::debug("获取redis信息", [$message, $channel]);
        if ($message) {
            $body = array(
                'type' => SocketService::BROAD_CAST_TYPE,
                'channel' => $channel,
                'content' => array_get($message, "body"),
                'time' => time(),
            );
            LogService::info(__FUNCTION__, $body);
            
            return Gateway::sendToGroup($channel, json_encode($body));
        }
        
        return false;
    }
}