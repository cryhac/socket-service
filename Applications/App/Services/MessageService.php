<?php
/**
 * Created by PhpStorm.
 * User: will
 * Date: 2017/4/14
 * Time: 下午8:59
 */

namespace App\Services;


use Workerman\Lib\Timer;
use GatewayWorker\Lib\Gateway;
use RuntimeException;

class MessageService extends Service
{
    /**
     * 添加消息推送计数器
     * @param $args
     * @return bool
     */
    public static function addPushMessageTimer($args)
    {
        LogService::debug("添加计数器", $args);
        Timer::add(1, [new static(), 'pushMessage'], $args);
        
        return true;
    }
    
    /**
     * 根据不同的房间(频道)消息推送
     * @param $args
     * @return bool
     */
    public function pushMessage($args)
    {
        $channel = $args;
        $count = Gateway::getClientCountByGroup($channel);
        if ($count == 0) {
            LogService::info("没有用户在线.不需要处理", [$channel]);
            
            return false;
        }
        $clientByGroup = Gateway::getClientInfoByGroup($channel);
        $onlineUser = array_unique(array_pluck($clientByGroup, "uid"));
        if ($onlineUser) {
            foreach ($onlineUser as $uid) {
                $data = [
                    'channel' => $args,
                    'uid' => $uid,
                ];
                self::processMessage($data);
            }
        }
        LogService::info("数据发送完毕");
        sleep(1);
        
        return true;
    }
    
    /**
     * 处理消息
     * @param $data
     * @return bool
     */
    private static function processMessage($data)
    {
        $args = array_get($data, "channel");
        $uid = array_get($data, "uid");
        $class = "App\\Channels\\".ucfirst($args) . "Message";
        if (empty($class)) {
            LogService::error("信息不合法。信息中必须包含channel信息");
            
            return false;
        }
        if (!class_exists($class)) {
            throw new RuntimeException($class . "不存在,请按照要求进行编写Task");
        }
        LogService::debug($class, [$args, $uid]);
        
        return (new $class())->handle($data);
    }
}