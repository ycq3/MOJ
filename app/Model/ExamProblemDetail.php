<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/6 0006
 * Time: 17:28
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class ExamProblemDetail extends Model
{
    //关闭修改时间戳
    public $timestamps = false;
    public function getLanguageAttribute($value)
    {
        return explode(',', $value);
    }

    public function setLanguageAttribute($value)
    {
        $this->attributes['language']=implode(",", $value);
    }
}