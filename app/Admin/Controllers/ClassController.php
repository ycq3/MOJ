<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Admin\Controllers;

use App\Model\Classes;
use App\Model\Students;
use Encore\Admin\Form;
use Encore\Admin\Form\NestedForm;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use function foo\func;

class ClassController extends Controller
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
        return Admin::grid(Classes::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('名称')->sortable();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Classes::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('name','名称');
            $form->display('students','学生列表')->with(function ($students){
                $str='<table class="table table-sm table-hover table-dark"><tr class="thead-light"><th>学号</th><th>姓名</th></tr>';
                foreach ($students as $student){
                    $str.="<tr><td>{$student['student_id']}</td><td>{$student['name']}</td></tr>";
                }
                $str.='</table>';
                return $str;
            });
        });
    }
}
