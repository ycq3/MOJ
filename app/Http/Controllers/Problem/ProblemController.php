<?php

namespace App\Http\Controllers\Problem;

use App\Model\Problem;
use App\Model\ProblemDetail;
use App\Model\ProblemSubmit;
use App\Model\ProblemTag;
use App\Model\RunResult;
use App\Model\Syslanguage;
use App\Model\Tag;
use App\Model\TagToProblem;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use function var_dump;

class ProblemController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        //标签查询, 多标签取并
        $selected_tags = $request->tag;
        if ($selected_tags != null) {
            $problems = null;
            $problems = ProblemTag::whereIn('tag_id', $selected_tags);
            $problems = $problems->join('problems','problem_id','id');
        } else {
            $problems = Problem::query();
        }

        //关键字查询
        $keyword = $request->get('keyword');
        if ($keyword != null) {
            $keywords[] = explode(' ', $keyword);
            $keywords = $keywords[0];
            foreach ($keywords as $i) {
                $problems->where('title', 'like', '%' . $i . '%');
            }
        }

        //分页显示
        $problems = $problems->paginate(50);
/*
        //数据处理
        $data = $problems->map(function ($problem) {
            return [
                'id' => $problem->id,
                'title' => $problem->title,
                'submited' => $problem->submited,
                'difficult' => $problem->difficulty,
                'total'=>$problem->total
            ];
        });

        dump($data);
        //设置表头
        $tableheader = ['ID', '题目', '提交数量', '难度'];
*/
        //回传提交数据
        $data=$problems->appends(['tag' => $selected_tags, 'keyword' => $keyword]);
        return $data;
        /*return view('problem.all', [
            'table_header' => $tableheader,
            'table_data' => $data,
            'paginator' => $problems->render(),
            'tags' => $tags,
            'page' => $request->get('page'),
        ]);*/
    }

    /**
     * @return \Illuminate\Support\Collection|static
     */
    public function tags()
    {
        //获取分类标签
        $problem_tags = Tag::all();
        $tags = $problem_tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'tag_name' => $tag->name,
            ];
        });
        return $tags;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        //根据ID 获取问题
        $problemdetail = ProblemDetail::where('id', $id)->get();

        //格式化数据
        $data = $problemdetail->map(function ($detail) {
            $language=[];
            foreach ($detail->language as $i) {
                if (strlen($i) > 0) {
                    $language = array_add($language, $i, config('mnnuoj.language')[$i]);
                }
            }

            //考虑到大部分题目都是不限制语言的，如果直接存数据库太消耗空间
            if ($language == null) {
                $language = config('mnnuoj.language');
            }

            return [
                'id' => $detail->id,
                'title' => $detail->title,
                'describe' => $detail->describe,
                'input' => $detail->input,
                'output' => $detail->output,
                'sampleinput' => $detail->sampleinput,
                'sampleoutput' => $detail->sampleoutput,
                'hint' => $detail->hint,
                'source' => $detail->source,
                'time' => $detail->time,
                'memory' => $detail->memory,
                'other_time' => $detail->other_time,
                'other_memory' => $detail->other_memory,
                'language'=>$language
            ];
        });
        //无效ID显示404
        if ($data->count() != 1) {
            return App::abort(404);
        }

        $data = $data[0];
        return $data;
    }

    /**
     * @param $pid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function submit($pid)
    {
        $problem = ProblemDetail::find($pid);

        $language = [];

        foreach ($problem->language as $i) {
            if (strlen($i) > 0) {
                $language = array_add($language, $i, config('mnnuoj.language')[$i]);
            }
        }

        //考虑到大部分题目都是不限制语言的，如果直接存数据库太消耗空间
        if ($language == null) {
            $language = config('mnnuoj.language');
        }

        $data = [
            'pid' => $pid,
            'language' => $language,
        ];
        return view('problem.submit', $data);
    }

    /**
     * @param $sid
     * @return array
     */
    public function code_show($sid)
    {
        $submit = ProblemSubmit::where('id', $sid)->first();
        if ($submit == null) {
            //id错误404
            return App::abort(404);
        }

        if (true||$submit->user_id == Auth::user()->id) {
            $pass_case=$submit->pass_detail;
            $fail_case=$submit->problem->test_cases->pluck('input_file_name')->all();
            $fail_case=array_merge(array_diff($fail_case, $pass_case));
            $data = [
                'runid' => $submit->id,
                'problem_id'=>$submit->fake_id,
                'user' => User::find($submit->user_id)->name,
                'time' => $submit->time,
                'mem' => $submit->memory,
                'code' => $submit->code,
                'result' => $submit->result,
                'language' => config('mnnuoj.language')[$submit->language],
                'submit_time' => $submit->created_at->toDateTimeString(),
                'info'=>$submit->error,
                'pass_case'=>$pass_case,
                'fail_case'=>$fail_case,
            ];
        } else {
            //看别人代码，404掉
//            return App::abort(404);
        }
        return $data;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function status(Request $request)
    {

        $user_id = Auth::user()->id;

        $p_id = $request->p_id;
        $u_id = $request->u_id;
        $language = $request->language;
        $result = $request->result;
        $submit_list = ProblemSubmit::where('problem_id', $p_id);

        strlen($u_id) && $submit_list->where('user_id', $u_id);

        strlen($result) && $submit_list->where('result', $result);

        strlen($language) && $submit_list->where('language', $language);

        $submit_list=$submit_list->orderBy('id','desc')->paginate(25);

        $data = $submit_list->map(function ($i) {
            return [
                'runid' => $i->id,
                'pid' => $i->problem_id,
                'user_id'=>$i->user_id,
                'user' => User::find($i->user_id)->name,
                'time' => $i->time,
                'mem' => $i->memory,
                'code_len' => strlen($i->code),
                'result' => $i->result,
                'language' => $i->language,
                'submit_time' => $i->created_at->toDateTimeString(),
            ];
        });

        $submit_list->setCollection($data);
        return $submit_list;
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function code_submit(Request $request)
    {
        //问题id 用户id 语言 代码
        $user_id = Auth::user()->id;
        $problem_id = $request->pid;
        $code = $request->code;
        $language = $request->language;


        $submit = new ProblemSubmit();
        $submit->user_id = $user_id;
        $submit->problem_id = $problem_id;
        $submit->language = $language;
        $submit->code = $code;
        $submit->save();
        return $submit->id;
    }
}
