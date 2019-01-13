<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/1 0001
 * Time: 11:38
 */

namespace App\Http\Controllers\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Validation\UnauthorizedException;


trait ProxyHelpers
{
    public function authenticate($email,$password)
    {
        $client = new Client();
        //服务器本地校验

        $url = env('APP_URL').'/oauth/token';

        $params = array_merge(config('passport.proxy'), [
            'username' => $email,
            'password' => $password,
        ]);
        $respond = $client->request('POST', $url, ['form_params' => $params]);


        if ($respond->getStatusCode() !== 401) {
            return json_decode($respond->getBody()->getContents(), true);
        }

        throw new UnauthorizedException('账号或密码错误');
    }
}
