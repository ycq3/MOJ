<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-31
 * Time: 下午3:37
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;

class ExamProblemTestcase extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    public $primaryKey = 'testcase_id';
    protected $fillable = ['input', 'output', 'score'];
    protected $appends = ['input_file_name','output_file_name'];

    public function problem()
    {
        return $this->exam_problem();
    }

    public function exam_problem()
    {
        return $this->belongsTo(ExamProblem::class);
    }

//    public function setTestcaseIdAttribute($value)
//    {
//        $this->attributes['testcase_id']=$value;
//    }

    public function setInputAttribute($value)
    {
        if (!isset($this->attributes['testcase_id'])) {
            //新增
            $name = $this->problem->test_cases->count() + 1;
            $name .= '.in';
            //获取新上传的文件名
            $file_name = explode("/", $value);

            if (sizeof($file_name) == 4) {
                $to = str_replace($file_name[3], $name, $value);
            }
        } else {
            //替换原来
            $to = $this->attributes['input'];
        }

        //删除旧测试数据
        try {
            Storage::disk('admin')->delete($to);
        } catch (FileNotFoundException $e) {

        }
        //移动文件
        Storage::disk('admin')->move($value, $to);
        $this->attributes['input'] = $to;
    }

    public function setOutputAttribute($value)
    {
        if (!isset($this->attributes['testcase_id'])) {
            //新增
            $name = $this->problem->test_cases->count() + 1;
            $name .= '.out';
            //获取新上传的文件名
            $file_name = explode("/", $value);

            if (sizeof($file_name) == 4) {
                $to = str_replace($file_name[3], $name, $value);
            }
        } else {
            //替换原来
            $to = $this->attributes['output'];
        }

        //删除旧测试数据
        try {
            Storage::disk('admin')->delete($to);
        } catch (FileNotFoundException $e) {

        }
        //移动文件
        Storage::disk('admin')->move($value, $to);
        $this->attributes['output'] = $to;
    }

    public function getOutputFileNameAttribute()
    {
        return explode('/',$this->output)[3];
    }

    public function getInputFileNameAttribute()
    {
        return explode('/',$this->input)[3];
    }
}
