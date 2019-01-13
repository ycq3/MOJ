<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:06
 */

namespace App\Model;


class WebClient
{
    //用户ID
    protected $user_id;
    //连接客户端ID
    protected $client_id;
    //认证token
    protected $token;
    //上次连接时间
    protected $last_connect_time;
    //首次连接时间
    protected $connect_time;
    //唯一ID
    protected $uuid;

    public function __construct()
    {
        $this->last_connect_time=now();
    }

    public function setUserId($user_id)
    {
        $this->user_id=$user_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setClientId($client_id)
    {
        $this->client_id=$client_id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function setToken($token)
    {
        $this->token=$token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setConnectTime($connect_time)
    {
        $this->connect_time=$connect_time;
    }

    public function getConnectTime()
    {
        return $this->connect_time;
    }

    public function getLastConnectTime()
    {
        return $this->last_connect_time;
    }
}