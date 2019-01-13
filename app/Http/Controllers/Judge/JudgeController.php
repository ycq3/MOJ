<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-24
 * Time: 下午1:03
 */

namespace App\Http\Controllers\Judge;

;

use App\Events\JudgeEvent;
use App\Model\ProblemDetail;
use App\Model\ProblemSubmit;
use App\Model\ProblemTestcase;
use App\Model\RunResult;
use App\Services\JudgeLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JudgeController
{
    private $logger;
    private $judger;
    public function __construct(JudgeLogService $judgeLogService)
    {
        $this->logger=$judgeLogService;
        $this->judger=session('judge_login');
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        if ($password == 'admin') {
            session(['judge_login' => $username]);
        } else {
            $this->logger->info('登录失败'.'username='.$username.'password='.$password);
        }
    }

    /**
     * @param Request $request
     * @return int
     */
    public function check_login(Request $request)
    {
        if (session('judge_login') != '') {
            return 1;
        }
        return 0;
    }


    /**
     * @param Request $request
     */
    public function get_job(Request $request)
    {
        //oj_lang_set max_running
        $this->logger->info('获取待评测队列');
        $max_running = $request->max_running;
        $wait = ProblemSubmit::where('result', '<', 4)->orWhereNull('result')->take($max_running)->get();
//        dd($wait);
        foreach ($wait as $i) {
            echo $i->id . "\n";
        }
    }

    /**
     * @param Request $request
     * @return int
     */
    public function checkout(Request $request)
    {
        //sid result
        $id = $request->sid;
        $result = $request->result;
        $submit = ProblemSubmit::find($id);
        if ($submit) {
            $this->logger->info('更新状态成功');
            $submit->result=$result;
            $submit->save();

            return 1;
        } else {
            $this->logger->info('找不到提交,id='.$id.',结果'.$result);
            return 0;
        }
    }
    public function get_problem_info(Request $request)
    {
        $problem_id=$request->pid;
        $problem=ProblemDetail::find($problem_id);
        if($problem){
            $this->logger->info('获取题目,id='.$problem_id);
            echo $problem->time."\n";
            echo $problem->memory."\n";
            echo $problem->special_judge."\n";
        }else{
            $this->logger->info('未找到问题,id='.$problem_id);
        }
    }

    public function user_code_info(Request $request)
    {
        $submit_id=$request->sid;
        $this->logger->info('获取用户代码信息,id='.$submit_id);
        $submit=ProblemSubmit::find($submit_id);
        if($submit){
            echo $submit->problem_id."\n";
            echo $submit->user_id."\n";
            echo $submit->language."\n";
        }
    }

    public function user_code(Request $request)
    {
        $submit_id=$request->sid;
        $this->logger->info('获取用户代码,id='.$submit_id);
        $submit=ProblemSubmit::find($submit_id);
        if($submit){
            echo $submit->code."\n";
        }
    }

    public function update_solution(Request $request)
    {
        $submit_id=$request->sid;
        $this->logger->info('评测机更新结果,id='.$submit_id);
        $this->logger->info('更新用户代码,id='.$submit_id);
        $submit=ProblemSubmit::find($submit_id);
        $submit->result=$request->result;
        $submit->time=$request->time;
        $submit->memory=$request->memory;
        $submit->pass_detail=$request->pass_detail;
        //实时作弊检测
        //$submit->sim=$request->sim;
        //$submit->simid=$request->sim_id;
        $submit->pass_rate=$request->pass_rate;
        $submit->save();

        $result=new RunResult($submit);
        event(new JudgeEvent($result));
    }

    public function test_data_list(Request $request)
    {
        $problem_id=$request->pid;
        $this->logger->info('获取测试数据组数,id='.$problem_id);
        $test_case=ProblemTestcase::where('problem_id',$problem_id)->get();

        if($test_case){
            foreach ($test_case as $i){
                $file_name=explode("/",$i->input);
                if(sizeof($file_name)==4){
                    echo $file_name[3]."\n";
                }
                $file_name=explode("/",$i->output);
                if(sizeof($file_name)==4){
                    echo $file_name[3]."\n";
                }
            }
        }
    }

    public function get_test_data_info(Request $request)
    {
        $filename=$request->filename;
        $problem_id=$request->pid;
        echo Storage::disk('admin')->lastModified('test_case\\problem\\'.$problem_id."\\".$filename);
    }

    public function get_test_data(Request $request)
    {
        $filename=$request->filename;
        $problem_id=$request->pid;
        $this->logger->info('下载测试数据,problem='.$problem_id.',filename='.$filename);
        echo Storage::disk('admin')->get('test_case\\problem\\'.$problem_id."\\".$filename);
    }

    public function upload_info(Request $request)
    {
        $submit_id=$request->sid;
        $info=$request->info;
        $submit=ProblemSubmit::find($submit_id);
        $submit->error=$info;
        $submit->save();
    }
}
