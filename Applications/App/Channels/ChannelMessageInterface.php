<?php
/**
 * Created by PhpStorm.
 * User: will
 * Date: 2017/6/27
 * Time: 上午9:59
 */
namespace App\Channels;

interface ChannelMessageInterface
{
    public function handle($data = []);
}