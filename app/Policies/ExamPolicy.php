<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/4 0004
 * Time: 15:10
 */

namespace App\Policies;


use App\Model\Exam;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Cache;

class ExamPolicy
{
    use HandlesAuthorization;

    public function  __construct()
    {

    }

    public function before()
    {

    }

    public function enterExam(User $user, Exam $exam, $key)
    {
        if ($exam == null) {
            return false;
        }
        //'public', 'private', 'class'
        if ($exam->type == 0) {

        } else if ($exam->type == 1) {
            $chache_index=$user->id.'_e'.$exam->id.'_key';
            if($key==null){
                if(Cache::has($chache_index)){
                    $key=Cache::get($chache_index);
                }
            }

            if ($key == $exam->password) {
                Cache::put($chache_index,$key,10);
            } else {
                //密码不对不给放行
                return false;
            }
        } else {
            $flag=false;
            foreach ($exam->classes as $class) {
                if($user->class_id==$class){
                    $flag=true;
                }
            }
            if($flag||$exam->classes==null){

            }else{
                return false;
            }
        }
        return true;
    }
}