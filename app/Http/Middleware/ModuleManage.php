<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Traits\ResponseHelpers;
use App\Http\Controllers\Traits\ProxyHelpers;
use App\Model\Sysconfig;
use Closure;
use Illuminate\Support\Facades\Auth;

class ModuleManage
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
        $disable_module=Sysconfig::where('status',0)->get();

        foreach ($disable_module as $i){
            $module=$i->module;
            if($request->is($module.'*')){
                return $this->failed('当前模块关闭 (code:ModuleManage Forbidden)');
            }
        }
        return $next($request);
    }
}
