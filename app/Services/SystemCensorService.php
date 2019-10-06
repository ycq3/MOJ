<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/3 0003
 * Time: 15:13
 */

namespace App\Services;


use AipImageCensor;
use function env;


class SystemCensorService
{
    private $APP_ID, $API_KEY, $SECRET_KEY;

    public function __construct()
    {
        $this->APP_ID = env('BAIDU_APP_ID');
        $this->API_KEY = env('BAIDU_API_KEY');
        $this->SECRET_KEY = env('BAIDU_SECRET_KEY');
    }

    public function text_censor($message)
    {
        $client = new AipImageCensor($this->APP_ID, $this->API_KEY, $this->SECRET_KEY);
        $result=$client->antiSpam($message);
        return $result['result']['spam']==0;
    }
}