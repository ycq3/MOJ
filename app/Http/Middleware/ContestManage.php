<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Http\Middleware;

use App\Http\Controllers\Traits\ResponseHelpers;
use App\Model\Contest;
use App\Model\Sysconfig;
use Closure;
use Illuminate\Support\Facades\Auth;

class ContestManage
{
    use ResponseHelpers;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $contest=Contest::find($request->cid);
        $password=$request->password;
        if($user==null||!$user->can('enterContest',[$contest,$password])){
            return $this->failed('你没有权限进入 (code:ContestManage Forbidden)');
        }
        if($contest->start_time>now()){
            return $this->failed('还未到开始时间 (code:ContestManage Forbidden)');
        }
        return $next($request);
    }
}
