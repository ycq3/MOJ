<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/10/27 0027
 * Time: 17:00
 */

namespace App\Services;


use Illuminate\Support\Facades\Cache;

class WorkmanClientService
{
    private $user;

    public function __construct()
    {
        $this->user = Cache::get('workman_user');
    }

    public function sendMessageToUser($user_id, $message, $type = 'message')
    {

    }

    public function sendMessageToClient()
    {

    }
}