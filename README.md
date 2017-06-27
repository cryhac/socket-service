socket-service
=======
基于workerman-chat的GatewayWorker框架开发的一款高性能支持分布式部署的socket推送服务。

GatewayWorker框架文档：http://www.workerman.net/gatewaydoc/

 特性
======
 * 使用websocket协议
 * 多频道支持
 * 订阅支持
 * 广播支持
 * 掉线自动重连
 * 支持多服务器部署
 * 消息推送到redis.指定给某个频道的某个用户发送消息,支持广播频道的广播消息
  
下载安装
=====
1、git clone https://github.com/cryhac/socket-service

2、composer install

3、修改redis的配置信息。Applications/Config/config.php

启动停止(Linux系统)
=====
以debug方式启动  
```php start.php start  ```

以daemon方式启动  
```php start.php start -d ```

测试
=======
使用chrome的扩展Smart Websocket Client

默认地址:ws://127.0.0.1:7272/

登录：{"type":"login","uid":5,"channel":"channel1"}
发送消息测试 使用php test.php


登录：{"type":"login","uid":5,"channel":"broadcast"}
发送消息测试 使用php test.php


日志
=======
日志默认记录在log/service.log文件中

声明
=======
此项目旨在给学习使用workerman的提供一个参考实例。并没有经过生产环境的
大量测试,请酌情使用。

todo
=======
目前暂时没有做鉴权行为。如果有需要。下次加上。




