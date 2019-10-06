<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\GridActions\ShowRunDetail;
use App\Admin\Extensions\Tools\RejudgeSelected;
use App\Model\ProblemSubmit;
use App\Model\RunResult;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use function foo\func;
use Illuminate\Http\Request;

class JudgeController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('评测机评测队列');
            $content->description('全部提交');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('评测结果');
            $content->description('人工修改');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('评测结果');
            $content->description('新建提交');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(ProblemSubmit::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            $grid->id('ID')->sortable();
            $grid->problem_id('问题ID');
            $grid->column('用户ID/用户名')->display(function (){
                return $this->user->id.'/'.$this->user->name;
            });
            $grid->column('比赛ID/名称')->display(function(){
                return $this->contest?$this->contest->id.'/'.$this->contest->title:'';
            });
            $grid->column('问题ID/名称')->display(function(){
                $url=route('problem.edit',$this->problem->id);
                return "<a href='{$url}'>{$this->problem->id}/{$this->problem->title}</a>";
            });
            $grid->result('结果')->editable('select', RunResult::$result);;
            $grid->created_at('提交时间');
            $grid->updated_at('最后一次更新时间');

            $grid->filter(function ($filter) {
                $filter->equal('problem_id', '问题ID');
                $filter->equal('contest_id','比赛ID');
                $filter->equal('user_id','用户ID');
                $filter->equal('result','运行结果')->multipleSelect([
                    0=>'Waiting',
                    1=>'Rejudge',
                    2=>'Compile',
                    3=>'judging',
                    4=>'Accepted',
                    5=>'Presentation Error',
                    6=>'Wrong Answer',
                    7=>'Time Limit Exceeded',
                    8=>'Memory Limit Exceeded',
                    9=>'Output Limit Exceeded',
                    10=>'Runtime Error',
                    11=>'Compile Error',
                    12=>'Compiled',
                    13=>'Running'
                ]);
            });

            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                    $batch->add('重测', new RejudgeSelected(RunResult::$OJ_WT1));
               });
            });

            $grid->actions(function ($actions){
//                $actions->append(new ShowRunDetail($actions->getKey()));
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(ProblemSubmit::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->display('created_at', '提交时间');
            $form->display('updated_at', '结果更新时间');
            $form->select('language','语言')->options(config('mnnuoj.language'));
            $form->select('result','结果')->options(RunResult::$result);
            $form->textarea('code','代码');
            $form->textarea('error','错误日志');
        });
    }

    public function rejudge(Request $request)
    {
        foreach (ProblemSubmit::find($request->get('ids')) as $submit) {
            $submit->result = $request->get('action');
            $submit->save();
        }
    }
}
