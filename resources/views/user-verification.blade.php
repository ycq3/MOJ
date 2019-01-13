<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-30
 * Time: 下午11:21
 */
?>
点击此链接激活您的账户:
<a href="{{ $link = url('verification', $user->verification_token) . '?email=' . urlencode($user->email) }}"> {{ $link }}
</a>
