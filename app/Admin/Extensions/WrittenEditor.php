<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/24 0024
 * Time: 12:53
 */

namespace App\Admin\Extensions;


use function array_add;
use function dump;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form\Field;

class WrittenEditor extends Field
{
    // 定义视图
    protected $view = 'admin.written';
    protected $view_type = 1;
    protected static $css = [
        '/vendor/laravel-admin/AdminLTE/plugins/iCheck/all.css',
    ];

    protected static $js = [
        'vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js',
    ];


    public function render()
    {
        $this->addVariables(['view_type' => $this->view_type]);
        return parent::render();
    }

    /**
     * bind this
     * @param $to
     * @return $this
     */
    public function linked($to)
    {
        $script = <<<EOT
        $(document).on('ifChecked', "input[name='{$to}']",show_{$this->getElementClassString()});
        $(document).on('click', "{$this->getElementClassSelector()}_btn",add_{$this->getElementClassString()});
        
        function show_{$this->getElementClassString()}(){
            var type=$("input[name='{$to}']:checked").val();
            var htm="";
            switch(type){
            case "3":
            case "1":htm="<div></div><div class='btn btn-success {$this->id}_btn'>添加</div>";break;
            case "2":
            case "4":htm="<textarea class='form-control {$this->getElementClassString()}' id='{$this->id}' name='{$this->id}'placeholder='{$this->placeholder}'></textarea>";
            }
            $("{$this->getElementClassSelector()}_container").html(htm);
        }
        //show_{$this->getElementClassString()}();
        
        function add_{$this->getElementClassString()}(){
            var input_div=$(this).prev("div");
            input_div.html(input_div.html()+"<input type='text' name='{$this->id}[]' class='form-control ' style='margin-bottom: 10px' placeholder='{$this->placeholder}'>")
            //alert(radio_div.html());
        }
EOT;
        Admin::script($script);
        return $this;
    }

    public function view_type($view_type)
    {
        $this->view_type = $view_type ? $view_type : 1;
        return $this;
    }
}