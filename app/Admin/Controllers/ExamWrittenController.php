<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/18 0018
 * Time: 17:26
 */

namespace App\Admin\Controllers;

use App\Model\Written;
use App\Model\WrittenSet;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use function request;

class ExamWrittenController extends Controller
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

            $content->body($this->form($id)->edit($id));
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
        return Admin::grid(Written::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->title('名称');
            $grid->type('类型');
            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @param null $id
     * @return Form
     */
    protected function form($id = null)
    {
        return Admin::form(Written::class, function (Form $form) use ($id) {

            $form->display('id', 'ID');
            $form->text('title', '标题');
            $form->radio('type', '题型')->options([1 => '选择', 2 => '填空', 3 => '多选', 4 => '简答']);
            if ($id)
                $form->written('content', '题目')->linked('type')->view_type(Written::find($id)->type)->help('您可以尽可能多的增加选项框，系统将自动忽略空白内容选项框');
            else
                $form->written('content', '题目')->linked('type');
            $form->text('key','答案')->help('多个选项之间用“|”分割。如1|2|3');
            $form->number('score','分数');
            $form->listbox('written_set','问题分类')->options(WrittenSet::all()->pluck('name','id'));
        });
    }
}