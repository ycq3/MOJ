<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Admin\Controllers;

use App\Model\Exam;
use App\Model\ExamProblem;
use App\Model\ExamProblemType;
use App\Model\Problem;
use App\Model\Syslanguage;
use App\Model\Tag;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use function foo\func;
use Illuminate\Support\Facades\Storage;

class ExamProblemController extends Controller
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

            $content->header('header');
            $content->description('description');

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

            $content->header('header');
            $content->description('description');

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

            $content->header('header');
            $content->description('description');

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
        return Admin::grid(ExamProblem::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->title('名称');

            $grid->difficulty('难度')->display(function ($rate) {
                $html = "<i class='fa fa-star' style='color:#ff8913'></i>";
                return join('&nbsp;', array_fill(0, min(5, $rate), $html));
            })->sortable();
            $grid->submited('提交人数')->sortable();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(ExamProblem::class, function (Form $form) {
            $form->tab('基本设置', function ($form) {
                $form->display('id', 'ID');
                $form->text('title', '题目');
                $form->editor('detail.describe', '题目描述');
                $form->editor('detail.input', '输入描述');
                $form->editor('detail.output', '输出描述');
                $form->textarea('detail.sampleinput','输入样例');
                $form->textarea('detail.sampleoutput','输出样例');
                $form->text('detail.hint', '提示');
            })->tab('测试用例测试', function ($form) {
                $form->number('detail.time','C++时间限制')->default(1000);
                $form->number('detail.memory','C++内存限制')->default(256);
                $form->number('detail.other_time','其他语言时间限制')->default(2000);
                $form->number('detail.other_memory','其他语言内存限制')->default(512);
                $option=config('mnnuoj.language');
                $form->multipleSelect('detail.language','语言设置')->options($option)->help('为空则支持所有语言');
                $form->html("<div class=\"alert alert-danger\" role=\"alert\">您不必在意文件名和后缀，系统将自动为您重命名。但是请<b class='h3'>同时上传输入和输出文件</b>，以免系统发生错误！</div>");
                $form->hasMany('test_cases','测试用例',function ($form){
                    $problem_id=$form->getForm()->model()->id;
                    $form->file('input','用例输入')->move('test_case/exam/'.$problem_id)->options([ 'showPreview' => false]);//->rules('required');
                    $form->file('output','用例输出')->move('test_case/exam/'.$problem_id)->options([ 'showPreview' => false]);//->rules('required');
                    $form->number('score','分数')->default(1);
                });
            })->tab('其他设置',function ($form){
                $form->listbox('type','问题分类')->options(ExamProblemType::all()->pluck('name','id'));
                $form->text('detail.source','题目来源');
            });
            $form->saved(function ($form){
                $form->model()->detail->title=$form->title;
                $form->model()->detail->save();
            });
        });
    }
}
