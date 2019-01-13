<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-11-11
 * Time: 下午8:35
 */

namespace App\Admin\Extensions\GridActions;
use Encore\Admin\Admin;

class ShowRunDetail
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    protected function script()
    {
        return <<<SCRIPT

$('.grid-check-row').on('click', function () {
    alert({$this->id});
});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return "<a href='' title='显示细节预览'><i class='fa fa-eye grid-check-row' data-id='{$this->id}'></i></a>";

    }

    public function __toString()
    {
        return $this->render();
    }
}
