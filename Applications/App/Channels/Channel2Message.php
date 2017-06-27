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

class Channel2Message implements ChannelMessageInterface
{
    
    public function handle($data = [])
    {
        $channel = array_get($data, "channel");
        $uid = array_get($data, "uid");
        $message = SocketService::getChannelMessage($channel, $uid);
        LogService::debug("获取redis信息", [$message, $channel, $uid]);
        if ($message) {
            $body = array(
                'uid' => array_get($message, "uid"),
                'channel' => $channel,
                'content' => array_get($message, "body"),
                'time' => time(),
            );
            LogService::info(__FUNCTION__, $body);
            
            return Gateway::sendToUid(array_get($message, "uid"), json_encode($body));
        }
        
        return false;
    }
}