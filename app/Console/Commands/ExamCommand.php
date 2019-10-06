<?php

namespace App\Console\Commands;

use App\Model\Exam;
use App\Model\ExamProblemSubmit;
use App\Model\ExamProblemUser;
use App\Model\Written;
use App\Model\WrittenAnswers;
use App\Model\WrittenWrittenSet;
use Illuminate\Console\Command;

class ExamCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exam {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exam command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $action = $this->argument('action');
        $this->info("Exam {$action} start!");
        $st=time();
        switch ($action){
            case 'correct':$this->correct();break;
            case 'mail_stu':
        }
        $this->info("Exam {$action} end! Time:".(time()-$st));
    }

    public function correct()
    {

        $exams = Exam::where('auto_correct', Exam::$Correct_on)->with('problem_type.problem.test_cases')->get();

        //每组测试数据对应的分数缓存
        $problem_data=[];

        /*
         * 考试平分流程
         * 计算程序题分数
         * 计算笔试题目分数
         * 计算总分
         * */
        foreach ($exams as $exam) {
            $exam->auto_correct=Exam::$Correct_running;
            $exam->save();

            /*
             * 程序提空题改卷流程
             * 获取问题对应的测试用例分数
             * 获取用户每次提交的通过测试用例
             * 计算每次提交的得分
             * 取最高分存入ExamProblemUser表
             * */
            foreach ($exam->problem_type as $problem_type) {
                foreach ($problem_type->problem as $problem) {
                    $testcase_data=[];
                    foreach ($problem->test_cases as $test_case) {
                        $testcase_data=array_merge($testcase_data,[$test_case->input_file_name=>$test_case->score]);
                    }
                    $problem_data=array_add($problem_data,$problem->id,$testcase_data);
                }
            }
            $user_record=ExamProblemUser::where('exam_id',$exam->id)->pluck('exam_problem_id','user_id');

            foreach ($user_record as $key=>$value){

                $user_submit=ExamProblemSubmit::where([['exam_id',$exam->id],['user_id',$key],['exam_problem_id',$value]])->get();
                $max=0;
                foreach($user_submit as $submit){
                    $score=0;
                    foreach($submit->pass_detail as $pass){
                        $score+=$problem_data[$submit->exam_problem_id][$pass];
                    }
                    $max=max($max,$score);
                }
                ExamProblemUser::where([['exam_id',$exam->id],['user_id',$key],['exam_problem_id',$value]])->update(['score'=>$max]);
            }

            /*
             * 笔试改卷
             * 确定题型
             * 判断答案
             * 给出分数
             * */
            $writtens = WrittenWrittenSet::whereIn('written_set_id', $exam->written_set)->with('written')->get();
            foreach ($writtens as $written) {
                $written = $written->written;

                if ($written->type == Written::$ExclusiveChoice) {
                    $size=count($written->content);
                    $answers = WrittenAnswers::where([['exam_id' , $exam->id], ['written_id' , $written->id]])->get();
                    foreach ($answers as $answer){
                        if($answer->answer==null){
                            $answer->score=0;
                            $answer->save();
                            continue;
                        }
                        $key=($answer->answer-1+$size-$answer->fake%$size)%$size;
                        $key=$key?$key:$size;
                        if($key==$written->key){
                            $answer->score=$written->score;
                        }else{
                            $answer->score=0;
                        }
                        $answer->save();
                    }
                }

            }

            $exam->auto_correct=Exam::$Correct_done;
            $exam->save();
        }
    }
}
