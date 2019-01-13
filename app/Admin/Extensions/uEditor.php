<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-5
 * Time: 下午4:50
 */

namespace App\Admin\Extensions;


use Encore\Admin\Form\Field;

/**
 * 百度编辑器
 * Class uEditor
 * @package App\Admin\Extensions
 */

class uEditor extends Field
{

    // 定义视图
    protected $view = 'admin.uEditor';

    // css资源
    protected static $css = [];

    // js资源
    protected static $js = [
        '/laravel-u-editor/ueditor.config.js',
        '/laravel-u-editor/ueditor.all.min.js',
        '/laravel-u-editor/lang/zh-cn/zh-cn.js'
    ];

    public function render()
    {
        $this->script = <<<EOT
        UE.delEditor('editor_{$this->id}');
   
        //变量重名 增加ID区分
        ue_{$this->id} = UE.getEditor('editor_{$this->id}'); // 默认id是ueditor
        ue_{$this->id}.ready(function () {
            ue_{$this->id}.execCommand('serverparam', '_token', $('meta[name="csrf-token"]').attr('content'));
        });
EOT;
        //
        return parent::render();
    }
}
