<?php
/**
 * Created by PhpStorm.
 * User: will
 * Date: 2017/6/26
 * Time: 上午11:12
 */

//redis的配置(其他配置请参见predis文档)
return [
    'redis' =>
        [
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => '6379',
            'options' => [
                'parameters' => [
//                    'password' => '',
                    'database' => 10,
                ],
            ]
        ]
];