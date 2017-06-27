<?php
/**
 * Created by PhpStorm.
 * User: will
 * Date: 2017/6/26
 * Time: 下午17:41
 */

namespace App\Services;

/**
 * Class LogService
 * @package App\Services
 * @method static LogService debug($message = "debug", $content = [1, 2]).
 * @method static LogService info($message = "info", $content = [1, 2]).
 * @method static LogService notice($message = "notice", $content = [1, 2]).
 * @method static LogService warning($message = "warning", $content = [1, 2]).
 * @method static LogService error($message = "error", $content = [1, 2]).
 * @method static LogService critical($message = "critical", $content = [1, 2]).
 * @method static LogService alert($message = "alert", $content = [1, 2]).
 * @method static LogService emergency($message = "emergency", $content = [1, 2]).
 */
class LogService extends Service
{
    
    /**
     * 支持日志级别
     * 比如：LogService::debug("debug消息",[1,2,3,4,5]);
     * 比如：LogService::info("info消息",[1,2,3,4,5]);
     * 比如：LogService::error("info消息",[1,2,3,4,5]); 等等
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments = [])
    {
        $app = parent::getApp();
        $log = $app->log;
        $content = array_get($arguments, 1);
        if (!is_array($content)) {
            return $log->{$name}(array_get($arguments, 0));
        }
        
        return $log->{$name}(array_get($arguments, 0), $content);
    }
}