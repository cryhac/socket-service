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

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

/**
 * 聊天主逻辑
 * 主要是处理 onMessage onClose
 */
use GatewayWorker\Lib\Gateway;
use App\Services\LogService;

class Events
{
    /**
     * 有消息时
     */
    public static function onMessage($client_id, $message)
    {
        // debug
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:" . json_encode($_SESSION) . " onMessage:" . $message . "\n";
        LogService::debug("onMessage", [$_SERVER, $_SESSION, $message, $client_id]);
        // 客户端传递的是json数据
        $message_data = json_decode($message, true);
        if (!$message_data) {
            return;
        }
        
        // 根据类型执行不同的业务
        switch ($message_data['type']) {
            // 客户端回应服务端的心跳
            case 'pong':
                return;
            // 客户端登录 message格式: {type:login, name:xx, channel:1} ，添加到客户端，广播给所有客户端xx进入聊天室
            case 'login':
                // 判断是否有频道
                if (!isset($message_data['channel'])) {
                    throw new \Exception("\$message_data['channel'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                }
                
                // 把频道昵称放到session中
                $channel = $message_data['channel'];
                $uid = $message_data['uid'];
                $_SESSION['channel'] = $channel;
                $_SESSION['uid'] = $uid;
                
                Gateway::joinGroup($client_id, $channel);
                Gateway::bindUid($client_id, $uid);
                $new_message = array(
                    'type' => 'login',
                    'channel' => array_get($message_data, "channel"),
                    'uid' => $uid,
                    'content' => "ok",
                    'time' => date('Y-m-d H:i:s'),
                );
                Gateway::sendToCurrentClient(json_encode($new_message));
                
                return;
            
            // 客户端发言 message: {type:say, to_client_id:xx, content:xx}
            case 'say':
                // 非法请求
                if (!isset($_SESSION['channel'])) {
                    throw new \Exception("\$_SESSION['channel'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $channel = $_SESSION['channel'];
                $client_name = $_SESSION['client_name'];
                
                // 私聊
                if ($message_data['to_client_id'] != 'all') {
                    $new_message = array(
                        'type' => 'say',
                        'from_client_id' => $client_id,
                        'from_client_name' => $client_name,
                        'to_client_id' => $message_data['to_client_id'],
                        'content' => "<b>对你说: </b>" . nl2br(htmlspecialchars($message_data['content'])),
                        'time' => date('Y-m-d H:i:s'),
                    );
                    Gateway::sendToClient($message_data['to_client_id'], json_encode($new_message));
                    $new_message['content'] = "<b>你对" . htmlspecialchars($message_data['to_client_name']) . "说: </b>" . nl2br(htmlspecialchars($message_data['content']));
                    
                    return Gateway::sendToCurrentClient(json_encode($new_message));
                }
                
                $new_message = array(
                    'type' => 'say',
                    'from_client_id' => $client_id,
                    'from_client_name' => $client_name,
                    'to_client_id' => 'all',
                    'content' => nl2br(htmlspecialchars($message_data['content'])),
                    'time' => date('Y-m-d H:i:s'),
                );
                
                return Gateway::sendToGroup($channel, json_encode($new_message));
        }
    }
    
    /**
     * 当客户端断开连接时
     * @param integer $client_id 客户端id
     */
    public static function onClose($client_id)
    {
        // debug
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onClose:''\n";
        LogService::debug(__FUNCTION__, [$_SERVER, $client_id]);
    }
    
}
