<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 15:19
 */

namespace App\Services;


use GuzzleHttp\Client;
use Mockery\Exception;

class WorkmanService
{
    public function deal($connection, $data)
    {
        $message = [];
        switch ($data['type']) {
            case 1:
                $message = 'connect';
                break;//心跳包
            case 2:
                $uid=$this->login($data['token']);
                $connection->uid=$uid;
                $message=['uid'=>$uid];
                break;//登录请求
        }
        return $message;
    }

    public function login($token)
    {
        try{
            $client=new Client();
            $response = $client->request('GET', 'http://localhost/api/user', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $token
                ],
            ]);
            if ($response->getStatusCode() != 200) {
                return null;
            }
            $resp=json_decode($response->getBody()->getContents(),true);
            return $resp['id'];
        }catch (Exception $exception) {
            return null;
        }
    }

}