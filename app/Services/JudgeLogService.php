<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-24
 * Time: 下午8:32
 */

namespace App\Services;


use Illuminate\Support\Facades\Log;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class JudgeLogService
{
    private $logger;

    public function __construct()
    {
        $judger = session('judge_login');
        if ($judger != '') {
            $logger = new Logger('judge-' . $judger);
            $logger->pushHander(new StreamHandler(storage_path('log/judge' . $judger . '.log'),Logger::INFO));
        } else {
            $logger = new Logger('judge');
            $logger->pushHandler(new StreamHandler(storage_path('logs/judge.log'), Logger::INFO));
        }
        $this->logger=$logger;
    }
    public function info($information)
    {
        //return;
        $this->logger->info($information);
    }
}
