<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-24
 * Time: 下午7:41
 */

namespace App\Model;


class RunResult
{

    public static $OJ_WT0 = 0;//等待
    public static $OJ_WT1 = 1;//Rejudge 等待
    public static $OJ_CI = 2;//编译中
    public static $OJ_RI = 3;//评测中？
    public static $OJ_AC = 4;//回答正确
    public static $OJ_PE = 5;//格式错误
    public static $OJ_WA = 6;//答案错误
    public static $OJ_TL = 7;//运行超时
    public static $OJ_ML = 8;//内存超限
    public static $OJ_OL = 9;//输出超限
    public static $OJ_RE = 10;//运行错误
    public static $OJ_CE = 11;//编译错误
    public static $OJ_CO = 12;//编译成功
    public static $OJ_TR = 13;//运行结束
    public static $result = [
        'Waiting',
        'Rejudge',
        'Compile',
        'judging',
        'Accepted',
        'Presentation Error',
        'Wrong Answer',
        'Time Limit Exceeded',
        'Memory Limit Exceeded',
        'Output Limit Exceeded',
        'Runtime Error',
        'Compile Error',
        'Compiled',
        'Running'
    ];

    public $submit_id;
    public $user_id;
    public $problem_id;
    public $time;
    public $memory;
    public $status;

    public function __construct(ProblemSubmit $submit)
    {
        $this->user_id=$submit->user_id;
        $this->submit_id=$submit->id;
        $this->problem_id=$submit->problem_id;
        $this->time=$submit->time;
        $this->memory=$submit->memory;
        $this->status=$submit->result;
    }
}
