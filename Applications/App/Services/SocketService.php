<?php
/**
 * Created by PhpStorm.
 * User: will
 * Date: 2017/06/26
 * Time: 下午16:21
 */

namespace App\Services;


class SocketService extends Service
{
    
    const CHANNEL_MESSAGE_PREFIX = 'channel:message:';
    const CHANNEL_MESSAGE_EXPIRE = 3600;//1个小时
    
    const BROAD_CAST_UID = 0;//广播用户uid=0
    const BROAD_CAST_TYPE = "broadcast";//广播类型
    
    
    public static function set($data)
    {
        $channel = array_get($data, "channel");
        $uid = array_get($data, "uid");
        $key = self::channelMessageKey($channel, $uid);
        $data['time'] = time();
        $redis = RedisService::getInstance();
        
        return $redis->setex($key, self::CHANNEL_MESSAGE_EXPIRE, json_encode($data));
    }
    
    /**
     * 用户频道信息
     * @param $channel
     * @param $uid
     * @return string
     */
    private static function channelMessageKey($channel, $uid)
    {
        return self::CHANNEL_MESSAGE_PREFIX . $channel . ":" . $uid;
    }
    
    /**
     * 拉取redis的信息进行推送
     * @param $channel
     * @param $user_id
     * @return mixed
     */
    public static function getChannelMessage($channel, $user_id)
    {
        $redis = RedisService::getInstance();
        $ret = $redis->get(self::channelMessageKey($channel, $user_id));
        $info = json_decode($ret, true);
        $redis->del(self::channelMessageKey($channel, $user_id));
        
        return $info;
    }
}