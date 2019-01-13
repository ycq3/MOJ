<?php

namespace App\Http\Controllers\Contest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ResponseHelpers;
use App\Http\Controllers\Traits\ProxyHelpers;
use App\Model\Contest;
use App\Model\ContestProblem;
use App\Model\ProblemSubmit;
use App\Model\RankListItem;
use App\Model\RunResult;
use App\Policies\ContestPolicy;
use App\User;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use function PHPSTORM_META\type;
use Whoops\Run;

class ContestController extends Controller
{
    use ResponseHelpers;

    //

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder|null
     */
    public function index(Request $request)
    {
        //标签查询，取交集
        $selected_tags = $request->tag;
        if ($selected_tags != null) {
            $contest = null;
            foreach ($selected_tags as $i) {
                Contest::where('type', $i);
            }
        } else {
            $contest = Contest::query();
        }

        $classid = null;
        if (Auth::user() != null) {
            $classid = Auth::user()->class_id;
        }

        $contest->where([
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
                $contest->where('title', 'like', '%' . $i . '%');
            }
        }

        $contest->orderBy('start_time', 'desc');
        //分页显示
        $contest = $contest->paginate(50);

        //回传提交数据
        $contest->appends(['tag' => $selected_tags, 'keyword' => $keyword]);

        return $contest;
    }

    //throttle:60,1

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check($id, Request $request)
    {
        $contest = Contest::find($id);
        $user = Auth::user();
        $password = $request->password;
        if ($user != null && $user->can('enterContest', [$contest, $password])) {
            return $this->succeed('成功');
        } else {
            return $this->failed('密码错误');
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function show($id)
    {
        $contest = Contest::find($id);
        $user = Auth::user();
        if ($user != null && $user->can('enterContest', [$contest, null])) {
            return array_add($contest, 'server_time', time());
        } else {
            return 'fail';
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function list($id)
    {

        $data = ContestProblem::where('contest_id', $id)->get()->map(function ($data) {
            return [
                'id' => $data->fake,
                'title' => $data->problem->title,
                'ac_flag' => $data->ac_flag,
                'accepted' => $data->accepted,
                'submited' => $data->submited,
            ];
        });
        //return view('contest.list', $data);
        return $data;
    }

    /**
     * @param $cid
     * @param $pid
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function problem($cid, $pid)
    {
        $problem = ContestProblem::where('contest_id', $cid)->where('fake', $pid)->first()->problem->detail;
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
                'time' => $problem->time,
                'memory' => $problem->memory,
                'other_time' => $problem->other_time,
                'other_memory' => $problem->other_memory,
                'cid' => $cid,
                'language' => $language
            ];
            return $data;
        } else {
            return redirect('error');
        }
    }

    /**
     * @param $cid
     * @param $pid
     * @return array
     */
    public function submit($cid, $pid)
    {
        $problem = ContestProblem::where('contest_id', $cid)->where('fake', $pid)->first()->problem->detail;
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
            'cid' => $cid
        ];
        //return view('contest.submit', $data);
        return $data;
    }

    public function code_submit(Request $request)
    {
        //问题id 用户id 语言 代码

        $user_id = Auth::user()->id;
        $contest_id = $request->cid;
        $problem_id = $request->pid;
        $r_id = ContestProblem::where([
            ['contest_id', $contest_id],
            ['fake', $problem_id]
        ])->first()->problem->id;

        $code = $request->code;
        $language = $request->language;

        $submit = new ProblemSubmit();
        $submit->user_id = $user_id;
        $submit->problem_id = $r_id;
        $submit->contest_id = $contest_id;
        $submit->language = $language;
        $submit->code = $code;
        $submit->save();
        $submit->id;
        return $submit->id;;
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
        $contest_id = $request->cid;
        $problem_id = $request->pid;

        $p_id = $request->p_id;
        $u_id = $request->u_id;
        $language = $request->language;
        $result = $request->result;
        $user_id = Auth::user()->id;

        if (strlen($problem_id)) {
            $r_id = ContestProblem::where([
                ['contest_id', $contest_id],
                ['fake', $problem_id]
            ])->first()->problem->id;
            $submit_list = ProblemSubmit::where([
                ['user_id', $user_id],
                ['problem_id', $r_id],
                ['contest_id', $contest_id]
            ]);
        } else {
            $problem_id = ContestProblem::where('contest_id', $contest_id)->pluck('fake','problem_id')->toArray();
            $submit_list = ProblemSubmit::where([
//                ['user_id', $user_id],
                ['contest_id', $contest_id]
            ]);
        }

        strlen($p_id) && $submit_list->where('problem_id', ContestProblem::where([
            ['contest_id', $contest_id],
            ['fake', $p_id]
        ])->first()->problem->id);

        strlen($u_id) && $submit_list->where('user_id', $u_id);

        strlen($result) && $submit_list->where('result', $result);

        strlen($language) && $submit_list->where('language', $language);

        $submit_list = $submit_list->orderBy('id', 'desc')->paginate(25);

        $data = $submit_list->map(function ($i) use ($problem_id) {
            return [
                'runid' => $i->id,
                //问题被删除旧忽略
                'pid' => is_array($problem_id) ? @$problem_id[$i->problem_id] : $problem_id,
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
     * @param $cid
     * @return array|mixed
     */
    public function rank($cid)
    {
        $rank_name = 'contest_' . $cid . '_rank';
        if (Cache::has($rank_name)) {
            $data = Cache::get($rank_name);
        } else {
            $rank = collect();

            $id_map = [];//缓存映射，加快速度
            $ak = [];
            $problem_id = ContestProblem::where('contest_id', $cid)->get()->map(function ($i) use (&$id_map) {
                $id_map[$i->problem_id] = $i->fake;
                return $i->fake;
            });

            $contest=Contest::find($cid);

            $start_time = $contest->start_time;
            $end_time=$contest->end_time;
            //优化Rank
            ProblemSubmit::where([['contest_id', $cid],['']])->chunk(50, function ($submit) use ($rank, $id_map, &$ak) {
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

                    if(!isset($id_map[$item->problem_id])){
                        //如果题目被删除，跳过
                        continue;
                    }

                    //计算AK用户
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

            $rank=$rank->sortByDesc(function ($rank_list_item, $key) use ($start_time) {
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
            $rank=$rank->sortByDesc('accept');


            $data = [
                'problem_id' => $problem_id,
                'rank' => $rank->values(),
                'ak' => $ak,
                'rank_time' => now(),
            ];
            Cache::put($rank_name, $data, 1);
        }
        //return view('contest.rank', $data);
        return $data;
    }
}
