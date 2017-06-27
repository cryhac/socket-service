<?php
/**
 * Created by PhpStorm.
 * User: will
 * Date: 2017/6/26
 * Time: 上午11:02
 */

namespace App\Services;

class Service
{
    
    private static $_app = null;
    
    /**
     * basePath
     * @param string $path
     * @return string
     */
    public static function basePath($path = '')
    {
        $root_path = dirname(dirname(dirname(dirname(__FILE__))));
        
        return $root_path . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
    
    public static function applicationsPath()
    {
        return self::basePath("Applications") . DIRECTORY_SEPARATOR;
    }
    
    public static function configPath()
    {
        return self::applicationsPath() . DIRECTORY_SEPARATOR . "Config" . DIRECTORY_SEPARATOR;
    }
    
    public static function logPath()
    {
        return self::basePath("log") . DIRECTORY_SEPARATOR;
    }
    
    public static function getConfig($key = '')
    {
        $path = self::configPath();
        $config = require $path . "config.php";
        if (empty($key)) {
            return $config;
        }
        
        return array_get($config, $key);
        
    }
    
    public static function app($app)
    {
        self::$_app = $app;
    }
    
    public static function getApp()
    {
        return self::$_app;
    }
}