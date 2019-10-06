<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/2 0002
 * Time: 23:42
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class ExamExamProblemType extends Model
{
    protected $table='exam_exam_problem_type';

    public function type()
    {
        return $this->belongsTo(ExamProblemType::class,'exam_problem_type_id');
    }
}