<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\ExcelExpoter;
use App\Model\Classes;
use App\Model\Contest;
use App\Model\Exam;
use App\Model\ExamExamProblemType;
use App\Model\ExamProblem;
use App\Model\ExamProblemType;
use App\Model\Problem;
use App\Model\WrittenSet;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ExamController extends Controller
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

            $content->header('管理考试');
            $content->description('当前系统时间 ' . now());
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
        return Admin::grid(Exam::class, function (Grid $grid) {

            $grid->exporter(new ExcelExpoter());


            $grid->id('ID')->sortable();
            $grid->title('比赛名称')->editable();
            $grid->start_time('开始时间');
            $grid->end_time('结束时间');
            $grid->column('type', '类型')->display(function ($type) {
                if ($type == Exam::$PUBIC) {
                    return "<span style='color:green'>公开</span>";
                } else if ($type == Exam::$PRIVATE) {
                    return "<span style='color:red'>私有</span>";
                } else {
                    return "<span style='color:orange'>班级</span>";
                }
            })->sortable();
            $grid->problem_type('问题数量')->display(function () {
                return $this->problem_type()->count();
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
        return Admin::form(Exam::class, function (Form $form) {
            $form->tab('基础设置', function ($form) {
                $form->display('id', 'ID');
                $form->display('updated_at', '上次更新时间');
                $form->display('created_at', '创建时间');
                $form->text('title', '考试名称');
                $form->datetimeRange('start_time', 'end_time', '比赛时间')->help('现在时间是：' . now());
                $form->select('type', '比赛类型')->options([Exam::$PUBIC => '公共', Exam::$PRIVATE => '私有', Exam::$CLASS => '班级',Exam::$INSIDE=>'限制内部帐号']);
                $form->listbox('classes','班级')->options(Classes::all()->pluck('name','id'));
                $form->password('password', '考试密码');
            })->tab('题目设置',function($form){
                $form->html("<div class=\"alert alert-warning\" role=\"alert\">请勾选对应的题目分组，系统将自动随机出卷。但是请<b class='h3'>确保分组内的题目分值相同</b>，以免出现总分不一致！</div>");
                $form->checkbox('problem_type','题型')->options(ExamProblemType::all()->pluck('name','id'));
            })->tab('笔试设置',function ($form){
                $form->listbox('written_set','笔试题目集')->options(WrittenSet::all()->pluck('name','id'));
            })->tab('高级设置',function ($form){
                $options=[
                    Exam::$Correct_off=>'关闭自动改卷',
                    Exam::$Correct_on=>'考试结束后自动改卷',
                    Exam::$Correct_done=>'终止改卷'
                ];
                $form->radio('auto_correct','自动改卷')->options($options)->default('1');
                $options=[
                    '0'=>'不监考',
                    '1'=>'自动查重',
                    '2'=>'笔试禁止使用其他软件',
                    '3'=>'编程题禁止使用其他软件（含IDE）',
                    '4'=>'笔试禁止使用其他软件，并开启查重（推荐）',
                    '5'=>'禁止使用其他软件，并开启查重',
                ];
                $form->radio('invigilate','监考模式')->options($options)->default('4');
                $options=[
                    Exam::$Rank_on=>'打开',
                    Exam::$Rank_off=>'关闭',
                    Exam::$Rank_limit=>'比赛前30分钟封榜'
                ];
                $form->radio('rank_control','榜单限制')->options($options);
                $form->switch('is_client_login','需要使用客户端登录');
            });
        });
    }
}
