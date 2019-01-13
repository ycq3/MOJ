<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;

class ProblemTestcase extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    public $primaryKey = 'testcase_id';
    protected $fillable = ['input', 'output', 'score'];
    protected $appends = ['input_file_name','output_file_name'];

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }

//    public function setTestcaseIdAttribute($value)
//    {
//        $this->attributes['testcase_id']=$value;
//    }

    public function setInputAttribute($value)
    {
        if(!isset($this->input))
        {
            //新增
            $name = $this->problem->test_cases->count();
            //有些莫名其妙，不能写同一行，要不会出bug
            $name+= $this->exists?0:1;
            $name.= '.in';
            //获取新上传的文件名
            $file_name = explode("/", $value);

            if (sizeof($file_name) == 4) {
                $to = str_replace($file_name[3], $name , $value);
            }
        }else{
            //替换原来
            $to = $this->attributes['input'];
        }

        //删除旧测试数据
        try {
            Storage::disk('admin')->delete($to);
        } catch (FileNotFoundException $e){

        }
        //移动文件
        Storage::disk('admin')->move($value, $to);
        $this->attributes['input'] = $to;
    }

    public function setOutputAttribute($value)
    {
        if(!isset($this->output))
        {
            //新增
            $name = $this->problem->test_cases->count();
            $name+= $this->exists?0:1;
            $name.= '.out';
            //获取新上传的文件名
            $file_name = explode("/", $value);

            if (sizeof($file_name) == 4) {
                $to = str_replace($file_name[3], $name , $value);
            }
        }else{
            //替换原来
            $to = $this->attributes['output'];
        }

        //删除旧测试数据
        try {
            Storage::disk('admin')->delete($to);
        } catch (FileNotFoundException $e){

        }
        //移动文件
        Storage::disk('admin')->move($value, $to);
        $this->attributes['output'] = $to;
    }

    public function getOutputFileNameAttribute()
    {
        return @explode('/',$this->output)[3];
    }

    public function getInputFileNameAttribute()
    {
        return @explode('/',$this->input)[3];
    }
}
