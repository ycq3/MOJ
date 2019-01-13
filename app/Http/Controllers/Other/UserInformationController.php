<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/30 0030
 * Time: 22:27
 */

namespace App\Http\Controllers\Other;

use App\Model\ProblemSubmit;
use App\Model\RunResult;
use App\Model\UserInformation;

class UserInformationController
{
    public function index($uid)
    {

        $user_info = UserInformation::where('user_id', $uid)->first();
        $list = ProblemSubmit::where('user_id', $uid)->groupBy('problem_id')->having('result', RunResult::$OJ_AC)->get();
        $pass_list = [];
        foreach ($list as $item) {
            $pass_list[$item->problem_id] = $item->problem->title;
        }

        $fail_list = [];
        $list2 = ProblemSubmit::where('user_id', $uid)->whereNotIn('problem_id', array_keys($pass_list))->get();
        foreach ($list2 as $item) {
            $fail_list[$item->problem_id] = $item->problem->title;
        }


        $data = [
            'id' => $user_info->user->id,
            'name' => $user_info->user->name,
            'school' => $user_info->school,
            'register_time' => $user_info->created_at->toDateTimeString(),
            'last_submit' => $user_info->updated_at->toDateTimeString(),
            'quote' => $user_info->quote,
            'accepted' => $user_info->accepted,
            'submitted' => $user_info->submitted,
            'pass_list' => $pass_list,
            'fail_list' => $fail_list
        ];
        return $data;
    }
}