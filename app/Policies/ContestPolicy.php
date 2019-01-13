<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Policies;

use App\Model\Contest;
use App\User;
use http\Env\Request;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;

class ContestPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {

    }

    public function enterContest(User $user, Contest $contest, $key)
    {
        if ($contest == null) {
            return false;
        }
        //'public', 'private', 'class'
        if ($contest->type == 0) {

        } else if ($contest->type == 1) {
            $chache_index=$user->id.'_c'.$contest->id.'_key';
            if($key==null){
                if(Cache::has($chache_index)){
                    $key=Cache::get($chache_index);
                }
            }

            if ($key == $contest->password) {
                Cache::put($chache_index,$key,10);
            } else {
                //密码不对不给放行
                return false;
            }
        } else {
            $flag=false;
            foreach ($contest->classes as $class) {
                if($user->class_id==$class){
                    $flag=true;
                }
            }
            if($flag||$contest->classes==null){

            }else{
                return false;
            }
        }
        return true;
    }
}
