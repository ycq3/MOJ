<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/9 0009
 * Time: 13:55
 */

namespace App\Admin\Extensions\Tools;


use Encore\Admin\Grid\Tools\BatchAction;

class RejudgeSelected extends BatchAction
{
    protected $action;

    public function __construct($action = 1)
    {
        $this->action = $action;
    }

    public function script()
    {
        return <<<EOT

$('{$this->getElementClass()}').on('click', function() {

    $.ajax({
        method: 'post',
        url: '{$this->resource}/rejudge',
        data: {
            _token:LA.token,
            ids: selectedRows(),
            action: {$this->action}
        },
        success: function () {
            $.pjax.reload('#pjax-container');
            toastr.success('操作成功');
        }
    });
});

EOT;

    }
}