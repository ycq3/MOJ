<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/15 0015
 * Time: 18:49
 */

namespace App\Admin\Extensions;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Form\Field\MultipleSelect;
use Encore\Admin\Form\Field\Select;
use Illuminate\Support\Str;

class LinkageListbox extends MultipleSelect
{
    // 定义视图
    protected $view = 'admin.LinkageListbox';

    public function dataSource($sourceUrl, $idField = 'id', $textField = 'text')
    {
        $script = <<<EOT
        $(document).off('change', "{$this->getElementClassSelector()}_select");
        function load_{$this->getElementClassString()} () {
            var target = $(this).closest('.fields-group').find("{$this->getElementClassSelector()}_list");
            if(this.value==null||this.value==-1){
                this.value='';
            }
            
            $.get("$sourceUrl?q="+this.value, function (data) {
                target.find("option").remove();
                    $(target).select2({
                        data: $.map(data, function (d) {
                            d.id = d.$idField;
                            d.text = d.$textField;
                            return d;
                        })
                    }).trigger('change');
            });
        }
        
        $(document).on('change', "{$this->getElementClassSelector()}_select",load_{$this->getElementClassString()});
        $(document).on('click', "{$this->getElementClassSelector()}_btn",add_{$this->getElementClassString()} );
        function add_{$this->getElementClassString()}(){
            var source = $(this).closest('.fields-group').find("{$this->getElementClassSelector()}_list");
            var target = $(this).closest('.fields-group').find("{$this->getElementClassSelector()}");
            var n=source.val();
            var m=true;
            target.find('option').each(function(){
                if($(this).val()==n){
                    var temp=target.val();
                    temp.push(''+n);     
                    target.val(temp).trigger("change"); 
                    m=false;
                }
            })
            m&&target.append('<option value='+n+' selected>'+n+'</option>');
        }
EOT;

        Admin::script($script);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $configs = array_merge([
            'allowClear' => true,
            'placeholder' => $this->label,
        ], $this->config);

        $configs = json_encode($configs);

        if (empty($this->script)) {
            $this->script =  <<<EOT
            $("{$this->getElementClassSelector()}_select").select2($configs);
            $("{$this->getElementClassSelector()}_list").select2($configs);
            $("{$this->getElementClassSelector()}").select2($configs);
            $("{$this->getElementClassSelector()}_select").change();
EOT;
        }

        return parent::render();
    }
}