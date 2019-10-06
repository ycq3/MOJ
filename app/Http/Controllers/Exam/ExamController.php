<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Model\Exam;
use App\Model\ExamExamProblemType;
use App\Model\ExamProblemDetail;
use App\Model\ExamProblemUser;
use App\Model\ExamProblemSubmit;
use App\Model\RankListItem;
use App\Model\RunResult;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\type;
use Whoops\Run;

class ExamController extends Controller
{
    //
    public function index(Request $request)
    {
        //标签查询，取交集
        $selected_tags = $request->tag;
        if ($selected_tags != null) {
            $exam = null;
            foreach ($selected_tags as $i) {
                Exam::where('type', $i);
            }
        } else {
            $exam = Exam::query();
        }

        $classid = null;
        if (Auth::user() != null) {
            $classid = Auth::user()->class_id;
        }

        $exam->where([
            ['type', '=', 2],
            ['classes', $classid]
        ])->orWhere([
            ['type', '=', 2],
            ['classes', 'like', $classid . ',%']
        ])->orWhere([
            ['type', '=', 2],
            ['classes', 'like', '%,' . $classid . ',%']
        ])->orWhere([
            ['type', '=', 2],
            ['classes', 'like', '%,' . $classid]
        ])->orWhere('type', '<>', 2);

        //关键字查询
        $keyword = $request->get('keyword');
        if ($keyword != null) {
            $keywords[] = explode(' ', $keyword);
            $keywords = $keywords[0];
            foreach ($keywords as $i) {
                $exam->where('title', 'like', '%' . $i . '%');
            }
        }

        $exam->orderBy('start_time', 'desc');

        //分页显示
        $exam = $exam->paginate(50);
        /* 丢给前端去处理了，这部分函数作废
                $tags = [
                    ['id' => 0, 'tag_name' => 'public'],
                    ['id' => 1, 'tag_name' => 'private'],
                    ['id' => 2, 'tag_name' => 'class'],
                ];

                //数据处理
                $data = $exam->map(function ($i) {
                    $tags = [
                        'public', 'private', 'class','internal'
                    ];

                    $status_name = ['Schedule', 'Running', 'Ended'];

                    $now = time();
                    $end_time = strtotime($i->endtime);
                    $start_time = strtotime($i->start_time);
                    if ($now < $start_time) {
                        $status = 0;
                    } else if ($now > $end_time) {
                        $status = 2;
                    } else {
                        $status = 1;
                    }

                    $lefttime = $end_time - $now;
                    if ($lefttime < 0) {
                        $lefttime = 0;
                    }

                    return [
                        'id' => $i->id,
                        'title' => $i->title,
                        'start_time' => $i->starttime,
                        'type' => $tags[$i->type],
                        'status' => $status_name[$status],
                        'left_time' => floor($lefttime / 3600) . '小时' . ($lefttime / 60 % 60) . '分钟' . ($lefttime % 60) . '秒',
                    ];
                });

        //设置表头
        $tableheader = ['ID', '标题', '开始时间', '剩余时间', '类型', '状态'];
        */
        //回传提交数据
        $data = $exam->appends(['tag' => $selected_tags, 'keyword' => $keyword]);

        return $data;

        /*return view('contest.all', [
            'table_header' => $tableheader,
            'table_data' => $data,
            'paginator' => $exam->render(),
            'tags' => $tags,
            'page' => $request->get('page'),
        ]);*/
    }

    public function show($id, Request $request)
    {
        $exam = Exam::find($id);
        $user = Auth::user();

        $password = $request->password;

        if ($user != null && $user->can('enterExam', [$exam, $password])) {
            return array_add($exam, 'server_time', time());
        } else {
            return 'fail';
        }
    }

    public function list($eid)
    {
        //考试结束无法查看题目详情
        $user_id = Auth::user()->id;
        $data = [];
        if (Cache::has('e_' . $eid . '_' . $user_id)) {
            $data = Cache::get('e_' . $eid . '_' . $user_id);
        } else {
            //找到题型
            $problem_types = Exam::find($eid)->problem_type;
            foreach ($problem_types as $type) {
                if (ExamProblemUser::where([['exam_problem_type_id', $type->id], ['user_id', $user_id], ['exam_id', $eid]])->count()) {
                    //如果用户已经有这个类型的题目跳过，防止因为考试中新增题目出现bug
                    continue;
                }
                //根据题型随机出一题
                $rand = rand(1, $type->problem->count());
                $problem = $type->problem->get($rand - 1);
                $user_problem = new ExamProblemUser;
                $user_problem->exam_id = $eid;
                $user_problem->user_id = $user_id;
                $user_problem->exam_problem_type_id = $type->id;
                $user_problem->exam_problem_id = $problem->id;
                $user_problem->save();
            }
            $data = ExamProblemUser::where([['user_id', $user_id], ['exam_id', $eid]])->with('problem')->get();
            $data = $data->map(function ($i) {
                return [
                    'exam_id' => $i->exam_id,
                    'exam_problem_type_id' => $i->exam_problem_type_id,
                    'user_score' => $i->score,
                    'title'=>$i->problem->title,
                    'score'=>$i->problem->score,
                    'difficulty'=>$i->difficulty
                ];
            });
            Cache::put('e_' . $eid . '_' . $user_id, $data, 10);
        }
        return $data;
    }

    public function problem($eid, $pid)
    {
        $user_problem = ExamProblemUser::where([['exam_id', $eid], ['exam_problem_type_id', $pid]])->first();
        $problem = ExamProblemDetail::where('id', $user_problem->exam_problem_id)->first();

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

        //真实ID不能给
        if ($problem) {
            $data = ['id' => $pid,
                'title' => $problem->title,
                'describe' => $problem->describe,
                'input' => $problem->input,
                'output' => $problem->output,
                'sampleinput' => $problem->sampleinput,
                'sampleoutput' => $problem->sampleoutput,
                'hint' => $problem->hint,
                'source' => $problem->source,
                'language'=>$language,
                'time' => $problem->time,
                'memory' => $problem->memory,
                'other_time' => $problem->other_time,
                'other_memory' => $problem->other_memory,
                'eid' => $eid
            ];
            return $data;
        } else {
            return redirect('error');
        }
    }

    /**
     * @param $eid
     * @param $pid
     * @return array
     */
    public function submit($eid, $pid)
    {
        $user_problem = ExamProblemUser::where([['exam_id', $eid], ['exam_problem_type_id', $pid]])->first();
        $problem = ExamProblemDetail::where('id', $user_problem->exam_problem_id)->first();
        $language = [];

        foreach ($problem->language as $i) {
            if (strlen($i) > 0) {
                $language = array_add($language, $i, config('mnnuoj.language')[$i]);
            }
        };
        //考虑到大部分题目都是不限制语言的，如果直接存数据库太消耗空间
        if ($language == null) {
            $language = config('mnnuoj.language');
        }

        $data = [
            'id' => $pid,
            'language' => $language,
            'eid' => $eid
        ];
        return $data;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function code_submit(Request $request)
    {
        //问题id 用户id 语言 代码
        $user_id = Auth::user()->id;
        $exam_id = $request->eid;
        $problem_id = $request->pid;
        $user_problem = ExamProblemUser::where([['exam_id', $exam_id], ['exam_problem_type_id', $problem_id]])->first();
        $r_id = $user_problem->exam_problem_id;

        $code = $request->code;
        $language = $request->language;

        $submit = new ExamProblemSubmit();
        $submit->user_id = $user_id;
        $submit->problem_id = $r_id;
        $submit->exam_id = $exam_id;
        $submit->language = $language;
        $submit->code = $code;
        $submit->save();
        return $submit->id;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function status_all(Request $request)
    {
        return $this->status($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function status(Request $request)
    {
        $exam_id = $request->eid;
        $problem_id = $request->pid;

        $p_id = $request->p_id;
        $u_id = $request->u_id;
        $language = $request->language;
        $result = $request->result;
        $user_id = Auth::user()->id;

        if (strlen($problem_id)) {
            $r_id = ExamProblemUser::where([
                ['exam_id', $exam_id],
                ['exam_problem_type_id', $problem_id]
            ])->first()->problem->id;
            $submit_list = ExamProblemSubmit::where([
                ['user_id', $user_id],
                ['problem_id', $r_id],
                ['exam_id', $exam_id]
            ]);
        } else {
            $problem_id = ExamproblemUser::where([['exam_id', $exam_id],['user_id',$user_id]])->pluck('exam_problem_type_id','exam_problem_id')->toArray();
            $submit_list = ExamProblemSubmit::where([
                ['user_id', $user_id],
                ['exam_id', $exam_id]
            ]);
        }

        strlen($p_id) && $submit_list->where('problem_id', ExamProblemUser::where([
            ['exam_id', $exam_id],
            ['exam_problem_type_id', $problem_id]
        ])->first()->problem->id);

        strlen($u_id) && $submit_list->where('user_id', $u_id);

        strlen($result) && $submit_list->where('result', $result);

        strlen($language) && $submit_list->where('language', $language);

        $submit_list = $submit_list->orderBy('id', 'desc')->paginate(25);

        $data = $submit_list->map(function ($i) use ($problem_id) {
            return [
                'runid' => $i->id,
                'pid' => is_array($problem_id) ? $problem_id[$i->problem_id] : $problem_id,
                'user_id' => $i->user_id,
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
     * @param $eid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rank($eid)
    {
        $exam=Exam::find($eid);
        $rank_control=$exam->rank_control;
        $end_time=Carbon::parse($exam->end_time);

        if($rank_control==Exam::$Rank_Off||$rank_control==Carbon::now()->between($end_time->subMinute(30),$end_time)){
            return $this->fail('Rank关闭');
        }
        $rank_name = 'exam_' . $eid . '_rank';
        if (false&&Cache::has($rank_name)) {
            $data = Cache::get($rank_name);
        } else {
            $rank = collect();

            $id_map = [];//缓存映射，加快速度
            $ak = [];
            $problem_id = ExamExamProblemType::where('exam_id', $eid)->get()->map(function ($i) use (&$id_map) {
                foreach ($i->type->problem as $problem){
                    $id_map[$problem->id] = $i->exam_problem_type_id;
                }
                return $i->exam_problem_type_id;
            });

            //优化Rank
            ExamProblemSubmit::where('exam_id', $eid)->chunk(50, function ($submit) use ($rank, $id_map, &$ak) {
                foreach ($submit as $item) {
                    //去除未评测结果
                    if ($item->result == RunResult::$OJ_WT0 ||
                        $item->result == RunResult::$OJ_WT1 ||
                        $item->result == RunResult::$OJ_CI ||
                        $item->result == RunResult::$OJ_RI ||
                        $item->result == RunResult::$OJ_CO ||
                        $item->result == RunResult::$OJ_TR) {
                        continue;
                    }

                    //计算首A用户
                    if ($item->result == RunResult::$OJ_AC) {
                        if (!isset($ak[$id_map[$item->problem_id]]['first']) || $ak[$id_map[$item->problem_id]]['first'] > $item->created_at) {
                            $ak[$id_map[$item->problem_id]]['first'] = $item->created_at;
                            $ak[$id_map[$item->problem_id]]['user_id'] = $item->user_id;
                        }
                    }

                    //初始化用户记录
                    if (!$rank->has($item->user_id)) {
                        $rank_item = new RankListItem();
                        $rank_item->user_id = $item->user_id;
                        $rank_item->user_name = $item->user->name;

                        $fail = 0;
                        if ($item->result == RunResult::$OJ_WA ||
                            $item->result == RunResult::$OJ_PE ||
                            $item->result == RunResult::$OJ_TL ||
                            $item->result == RunResult::$OJ_RE ||
                            $item->result == RunResult::$OJ_ML) {
                            $fail = 1;
                        }

                        $rank_item->problem_data = [];
                        $rank_item->problem_data = array_add($rank_item->problem_data, $id_map[$item->problem_id], [
                            'result' => $item->result == RunResult::$OJ_AC,
                            'fail' => $fail,
                            'time' => $item->created_at,
                        ]);
                        $rank->put($item->user_id, $rank_item);
                    } else {//已存在记录处理
                        $rank_item = $rank->get($item->user_id);

                        //如果本题已经存在数据，且数据为AC则继续
                        if (isset($rank_item->problem_data[$id_map[$item->problem_id]]) && $rank_item->problem_data[$id_map[$item->problem_id]]['result']) {
                            continue;
                        }

                        $fail = 0;
                        if ($item->result == RunResult::$OJ_WA ||
                            $item->result == RunResult::$OJ_PE ||
                            $item->result == RunResult::$OJ_TL ||
                            $item->result == RunResult::$OJ_RE ||
                            $item->result == RunResult::$OJ_ML) {
                            $fail = 1;
                        }
                        //如果本题已经有数据，更新数据
                        if (isset($rank_item->problem_data[$id_map[$item->problem_id]])) {
                            $rank_item->problem_data[$id_map[$item->problem_id]]['result'] = $item->result == RunResult::$OJ_AC || $rank_item->problem_data[$id_map[$item->problem_id]]['result'];
                            $rank_item->problem_data[$id_map[$item->problem_id]]['fail'] += $fail;
                            $rank_item->problem_data[$id_map[$item->problem_id]]['time'] = $item->created_at;
                        } else {
                            $rank_item->problem_data = array_add($rank_item->problem_data, $id_map[$item->problem_id], [
                                'result' => $item->result == RunResult::$OJ_AC,
                                'fail' => $fail,
                                'time' => $item->created_at,
                            ]);
                        }
                    }
                }
            });
            $start_time = Exam::find($eid)->start_time;
            $rank->sortByDesc(function ($rank_list_item, $key) use ($start_time) {
                $accept = 0;
                $penalty = 0;
                foreach ($rank_list_item->problem_data as &$data) {
                    if ($data['result']) {
                        $accept++;
                    }
                    $data['time'] = strtotime($data['time']) - strtotime($start_time) + $data['fail'] * 20 * 60;
                    $penalty += $data['time'];
                }
                $rank_list_item->accept = $accept;
                $rank_list_item->penalty = $penalty;
                return $rank_list_item->penalty;
            });
            $rank->sortBy('accept');


            $data = [
                'problem_id' => $problem_id,
                'rank' => $rank->values(),
                'ak' => $ak,
                'rank_time' => now(),
            ];
            Cache::put($rank_name, $data, 1);
        }
        return $data;
    }

    public function code_show($sid)
    {

    }
}
