<?php

/**
 * Created by PhpStorm.
 * User: will
 * Date: 2017/6/26
 * Time: 上午11:49
 */
namespace App;

use App\Services\Service;
use Illuminate\Log\Writer;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


class Application
{
    /**
     * The registered type aliases.
     *
     * @var array
     */
    protected $aliases = [];
    
    public $log;
    
    public function __construct($basePath = null)
    {
        return $this;
    }
    
    
    public function log()
    {
        $logger = new Logger('production');
        
        $log = new Writer(
            $logger
        );
        $log_path = Service::logPath();
        $logger->pushHandler(new StreamHandler($log_path . '/service.log'));
        $logger->pushHandler(new FirePHPHandler());
        $this->log = $log;
    }
    
}