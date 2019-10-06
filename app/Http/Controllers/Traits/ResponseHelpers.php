<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-8-1
 * Time: 下午1:24
 */

namespace App\Http\Controllers\Traits;

trait ResponseHelpers
{

    /**
     * @param bool $status true stand for succeed,false stand for fail
     * @param string $respond the response message content
     * @return string  json string
     */
    public function response(bool $status, $respond)
    {
        return response()->json(['status' => $status, is_string($respond) ? 'message' : 'data' => $respond]);
    }


    /**
     * @param string $respond the success response message content
     * @return string json string
     */
    public function succeed($respond = 'Request success!')
    {
        return $this->response(true, $respond);
    }


    /**
     * @param string $respond the fail response message content
     * @return string json string
     */
    public function failed($respond = 'Request failed!')
    {
        return $this->response(false, $respond);
    }

}
