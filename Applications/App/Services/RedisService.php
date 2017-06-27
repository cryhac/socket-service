<?php
/**
 * Created by PhpStorm.
 * User: will
 * Date: 2017/4/14
 * Time: 下午8:59
 */

namespace App\Services;

use Illuminate\Redis\Connectors\PredisConnector;

class RedisService extends Service
{
    
    private static $redis;
    
    public static function getInstance()
    {
        $config = self::getConfig("redis");
        LogService::debug("config的值", $config);
        if (empty($config)) {
            throw new \RuntimeException("请配置redis信息");
        }
        $options = array_pull($config, "options");
        if (!self::$redis) {
            return (new PredisConnector())->connect($config, $options);
        }
        
        return self::$redis;
    }
}