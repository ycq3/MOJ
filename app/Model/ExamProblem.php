<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-29
 * Time: 下午2:18
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class ExamProblem extends Model
{
    protected $fillable=['title','submited','difficulty'];
    //关闭时间戳
    public $timestamps = false;

    public function setTitleAttribute($value)
    {
        $this->attributes['title']=$value;
        $detail=$this->detail;
        $detail->title=$value;
        $detail->save();
    }

    //一个问题对应多个TestCase
    public function test_cases()
    {
        return $this->hasMany(ExamProblemTestcase::class);
    }

    public function detail()
    {
        return $this->hasOne(ExamProblemDetail::class, 'id');
    }

    public function type()
    {
        return $this->belongsToMany(ExamProblemType::class);
    }

    public function submit()
    {
        return $this->hasMany(ExamProblemSubmit::class);
    }
}
