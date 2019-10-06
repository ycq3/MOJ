<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/11/2 0002
 * Time: 12:13
 */

namespace App\Http\Controllers\Traits;


use AipImageCensor;
use function is_array;

trait Censor
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
        if (is_array($message)) {
            $message=implode($message);
        }
        $client = new AipImageCensor($this->APP_ID, $this->API_KEY, $this->SECRET_KEY);
        $result = $client->antiSpam($message);
        return $result['result']['spam'] == 0;

    }
}