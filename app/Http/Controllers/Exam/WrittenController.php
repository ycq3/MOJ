<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/18 0018
 * Time: 15:27
 */

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ResponseHelpers;
use App\Model\Exam;
use App\Model\WrittenAnswers;
use App\Model\WrittenWrittenSet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use function request;


class WrittenController extends Controller
{
    use ResponseHelpers;

    public function show($eid)
    {
        $exam = Exam::find($eid);
        $written_set = $exam->written_set;
        //准备数据
        foreach ($written_set as $item) {
            if (Cache::has('ew_' . $eid . '_' . $item)) {
                continue;
            }
            $writtens = WrittenWrittenSet::where('written_set_id', $item)->pluck('written_id');
            Cache::put('ew_' . $eid . '_' . $item, $writtens, '10');
        }

        $user_id = Auth::user()->id;
        foreach ($written_set as $item) {
            $writtens = Cache::get('ew_' . $eid . '_' . $item);
//            $writtens = $writtens->shuffle(); 随机题目顺序
            foreach ($writtens as $written) {
                if (WrittenAnswers::where([['user_id', $user_id], ['written_id', $written], ['exam_id', $eid]])->count()) {
                    continue;
                }
                $answer = new WrittenAnswers();
                $answer->exam_id = $eid;
                $answer->user_id = $user_id;
                $answer->written_id = $written;
                $answer->fake = rand(0, 50);
                $answer->save();
            }
        }

        $data = WrittenAnswers::where([['user_id', $user_id], ['exam_id', $eid]])->with('written')->get()->map(function ($item) {

            return [
                'id' => $item->id,
                'type' => $item->written->type,
                'fake' => $item->fake,
                'answer' => $item->answer,
                'score' => $item->written->score,
                'title' => $item->written->title,
                'content' => $item->written->content
            ];
        });

        return $data;
    }

    public function save($eid)
    {
        $select = request('select');

        foreach ($select as $item) {
            $answer = WrittenAnswers::find($item['id']);
            if (Auth::user()->id == $answer->user_id) {
                $answer->answer = $item['answer'];
                $answer->save();
            } else {
                return $this->failed('非法数据');
            }
        }
        return $this->succeed('提交成功');
    }
}