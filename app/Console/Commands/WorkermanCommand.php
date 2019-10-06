<?php

namespace App\Console\Commands;

use App\Handlers\WorkermanHandler;
use function env;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Workerman\Worker;

class WorkermanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'WorkerMan:console {action} {--d}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'start workerman';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        global $argv;
        $arg = $this->argument('action');

        $argv[0]='WorkerMan:console';
        $argv[1]=$arg;
        $argv[2]=$this->option('d')?'-d':'';

        switch ($arg) {
            case 'start':
                $this->info('workerman observer started');
                $this->start();
                break;
            case 'stop':
                $this->info('stoped');
                break;
            case 'restart':
                $this->info('restarted');
                break;
            case 'status':
                Worker::getStatus();
                break;
        }
    }

    private function start()
    {
        global $text_worker,$server_worker;
        // 创建一个Worker监听9130端口，使用websocket协议通讯
        // 证书最好是申请的证书
        $context = array(
            // 更多ssl选项请参考手册 http://php.net/manual/zh/context.ssl.php
            'ssl' => array(
                // 请使用绝对路径
                'local_cert'                 => '/www/wwwroot/oj.pipiqiang.cn/storage/SSL/1_oj.pipiqiang.cn_bundle.crt', // 也可以是crt文件
                'local_pk'                   => '/www/wwwroot/oj.pipiqiang.cn/storage/SSL/2_oj.pipiqiang.cn.key',
                'verify_peer'               => false,
                // 'allow_self_signed' => true, //如果是自签名证书需要开启此选项
            )
        );
        if(env('APP_SECURE')){
            $this->info('ssl 模式启动');
            $text_worker = new Worker("websocket://0.0.0.0:9130",$context);
            $text_worker->transport = 'ssl';
        }else{
            $this->info('普通模式启动');
            $text_worker = new Worker("websocket://0.0.0.0:9130");
        }

        // 启动4个进程对外提供服务
        $text_worker->count = 4;
        $handler1 = App::make(WorkermanHandler::class);
        $text_worker->onConnect = array($handler1,"handle_connection");
        $text_worker->onMessage = array($handler1,"handle_message");
        $text_worker->onClose = array($handler1,"handle_close");
        $text_worker->onWorkerStart=array($handler1,"handle_workstart");

        // 运行worker
        Worker::runAll();
    }

    protected function getArguments()
    {
        return array(
            array('action',InputArgument::REQUIRED,'start|stop|restart'),
        );
    }

}
