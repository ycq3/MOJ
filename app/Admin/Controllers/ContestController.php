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
use App\Model\ContestProblem;
use App\Model\Problem;
use App\Model\ProblemTag;
use App\Model\Tag;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use function foo\func;
use Illuminate\Http\Request;
use function strlen;
use function var_dump;

class ContestController extends Controller
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

            $content->header('管理比赛');
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
        return Admin::grid(Contest::class, function (Grid $grid) {

            $grid->exporter(new ExcelExpoter());

            $grid->id('ID')->sortable();
            $grid->title('比赛名称')->editable();
            $grid->start_time('开始时间');
            $grid->end_time('结束时间');
            $grid->column('type', '类型')->display(function ($type) {
                if ($type == Contest::$PUBIC) {
                    return "<span style='color:green'>公开</span>";
                } else if ($type == Contest::$PRIVATE) {
                    return "<span style='color:red'>私有</span>";
                } else {
                    return "<span style='color:orange'>班级</span>";
                }
            })->sortable();
            $grid->problems('问题数量')->display(function () {
//                return 1;
                return $this->problems()->count();
                //dd($this->count('problems'));
//                dd($this->withCount('problems')->get());
//                return $this->withCount('problems')->get()->problems_count;
            });

            $grid->filter(function ($filter) {
                $filter->like('title', '比赛名称');
                $filter->between('start_time', '开始时间（介于）')->datetime();
                $filter->between('end_time', '结束时间（介于）')->datetime();
                $filter->in('type', '比赛类型')->multipleSelect([Contest::$PUBIC => '公共', Contest::$PRIVATE => '私有', Contest::$CLASS => '班级']);
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

        return Admin::form(Contest::class, function (Form $form) {
            $form->tab('基础设置', function ($form) {
                $form->display('id', 'ID');
                $form->display('updated_at', '上次更新时间');
                $form->display('created_at', '创建时间');
                $form->text('title', '比赛名称');
                $form->datetimeRange('start_time', 'end_time', '比赛时间')->help('现在时间是：' . now());
                $form->select('type', '比赛类型')->options([Contest::$PUBIC => '公共', Contest::$PRIVATE => '私有', Contest::$CLASS => '班级']);
                $form->password('password', '比赛密码');
                $form->listbox('classes', '班级')->options(Classes::all()->pluck('name', 'id'));
            })->tab('题目设置', function ($form) {
                $form->linkagelistbox('problems','问题')->options(Tag::all()->pluck('name', 'id'))->dataSource('/admin/api/problem');
            });

            $form->saved(function (Form $form) {
                $problem = ContestProblem::where('contest_id', $form->model()->id)->get();
                $rand = rand(1, 100);
                $i = 1;
                foreach ($problem as $p) {
                    $p->setFake($rand + $i);
                    $i++;
                }
            });
        });
    }

    public function api_problem(Request $request)
    {
        $type_id = $request->get('q');
        if($type_id&&strlen($type_id)){
            return ProblemTag::with('problem')->where('tag_id',$type_id)->get()->map(function ($item){
                return [
                    'id'=>$item->problem->id,
                    'text'=>$item->problem->title.'-'.$item->problem->difficulty
                ];
            });
        }else{
            return ProblemTag::with('problem')->get()->map(function ($item){
                return [
                    'id'=>$item->problem->id,
                    'text'=>$item->problem->title.'-'.$item->problem->difficulty
                ];
            });
        }

    }
}
