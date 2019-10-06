<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/5 0005
 * Time: 11:26
 */

namespace App\Services;


use Jrean\UserVerification\Facades\UserVerification;

class VerificationService
{
    /**
     *
     */
    public function send($user)
    {
        UserVerification::generate($user);
        UserVerification::send($user, 'MNNU Online Judge System 邮箱激活');
    }
}