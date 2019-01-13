<?php

namespace App\Handlers;

use App\Http\Controllers\Traits\ResponseHelpers;
use App\Model\WebClient;
use App\Services\WorkmanService;
use ErrorException;
use Illuminate\Console\Command;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Mockery\Exception;
use Workerman\Lib\Timer;
use Workerman\Worker;

// 心跳间隔30秒
define('HEARTBEAT_TIME', 30);

class WorkermanHandler
{
    use ResponseHelpers;
    protected $uuid = 0;
    protected $workmanService;
    protected $client;

    public function __construct(WorkmanService $workmanService)
    {
        $this->workmanService = $workmanService;
    }

    //当客户端连上来时分配uid,并保存链接
    public function handle_connection($connection)
    {
        global $uuid, $client;
        //防止炸int
        if (9223372036854775807 == 0) {//64位系统
            $uuid %= 9223372036854775807;
        } else {
            $uuid %= 2147483647;
        }
        $uuid++;
        $connection->uuid = $uuid;
        $connection->connect_time = now();
        $client[$uuid] = $connection;
        $message = $this->succeed('connect')->content();
        $connection->send($message);
    }

    public function handle_message($connection, $data)
    {
        // 给connection临时设置一个lastMessageTime属性，用来记录上次收到消息的时间
        $connection->lastMessageTime = time();
        try {
            $data = json_decode($data, true);
            $resp = $this->workmanService->deal($connection, $data);
            $message = $this->succeed($resp)->content();
        } catch (Exception $exception) {
            $message = $this->failed($exception->getMessage())->content();
        }
        $connection->send($message);
    }

    //当客户端断开时
    public function handle_close($connection)
    {

    }

    public function handle_workstart($worker)
    {

        echo '启动心跳服务';
        //启动一个心跳服务
        Timer::add(1, function () use ($worker) {
            global $client;
            $time_now = time();
            $connected = collect();
            foreach ($worker->connections as $connection) {
                // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
                if (empty($connection->lastMessageTime)) {
                    $connection->lastMessageTime = $time_now;
                }
                // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
                if ($time_now - $connection->lastMessageTime > HEARTBEAT_TIME) {
                    $client[$connection->uuid] = null;
                    $connection->close();
                    continue;
                }
                $web_client = new WebClient();
                $web_client->setClientId(@$connection->uuid);
                $web_client->setUserId(@$connection->uid);
                $web_client->setConnectTime(@$connection->connect_time);
                $connected->push($web_client);
            }
            Cache::forever('workman_user', $connected);
        });
        echo  '启动控制服务';
        //启动一个后台控制服务，用来主动推送消息
        // 监听一个http端口
        $inner_http_worker = new Worker('http://0.0.0.0:9131');
        // 当http客户端发来数据时触发
        $inner_http_worker->onMessage = function ($http_connection, $data) use ($worker) {
            global $client;
            $_POST = $_POST ? $_POST : $_GET;
            // 推送数据的url格式 type=publish&to=uid&content=xxxx
            switch(@$_POST['type']){
                case 'dialog':
                case 'reload':
                case 'message':
                case 'goto':
                    $to = @$_POST['to'];
                    $message = @$_POST['content'];
                    // 有指定uid则向uid所在socket组发送数据
                    if($to){
                        if(isset($client[$to]))
                        $client[$to]->send($message);
                    }else{// 否则向所有uid推送数据
                        foreach($worker->connections as $connection) {
                            $connection->send($message);
                        }
                    }
                    // http接口返回，如果用户离线socket返回fail
                    if($to && !isset($client[$to])){
                        return $http_connection->send('offline');
                    }else{
                        return $http_connection->send('ok');
                    }


            }
            return $http_connection->send('fail');
        };
        try{
            $inner_http_worker->listen();
        }catch (\Exception $e){
            echo "子进程创建时会导致错误死循环,catch 异常终止创建";
        }
    }

}