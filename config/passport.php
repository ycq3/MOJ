<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/1 0001
 * Time: 11:42
 */
return [
    'proxy' => [
        'grant_type'    => env('OAUTH_GRANT_TYPE'),
        'client_id'     => env('OAUTH_CLIENT_ID'),
        'client_secret' => env('OAUTH_CLIENT_SECRET'),
        'scope'         => env('OAUTH_SCOPE', '*'),
    ],
];