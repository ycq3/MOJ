<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Admin\Controllers;

use App\Model\Classes;
use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class UserController extends Controller
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

            $content->header('管理注册用户');
            $content->description('全部用户');

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

            $content->header('用户信息');
            $content->description('编辑');

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

            $content->header('新增用户');
            $content->description('创建一个新用户');

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
        return Admin::grid(User::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('用户名');
            $grid->verified('邮箱验证')->display(function($value){
                return $value?'已激活':'未激活';
            });
            $grid->class_id('校内学生')->display(function($value){
                return $value?'校内':'否';
            });
            $grid->created_at('注册时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(User::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('name','用户名');
            $form->radio('information.sex','性别')->options([0 => '保密',1=> '男',2=>'女'])->default('0');
            $form->text('information.school','学校');
            $form->switch('verified','邮箱验证')->states([
                'on'=>['value'=>1,'text'=>'通过'],
                'off'=>['value'=>0,'text'=>'未验证']
            ]);
            $form->select('class_id','班级')->options(Classes::all()->pluck('name','id'));
            $form->email('email','邮箱');
            $form->display('created_at', '注册日期');
            $form->display('updated_at', '最后一次信息修改时间');
        });
    }
}
