<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Http\Middleware;

use App\Model\Contest;
use App\Model\Exam;
use App\Model\Sysconfig;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\ResponseHelpers;

class ExamManage
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
        $exam=Exam::find($request->eid);
        if($user==null||!$user->can('enterExam',[$exam,null])){
            return $this->failed('你没有权限进入 (code:ExamManage Forbidden)');
        }

        if(!$request->routeIs('exam_main')&&$exam->start_time>now()){
            return $this->failed('还未到开始时间 (code:ExamManage Forbidden)');
        }
        return $next($request);
    }
}
