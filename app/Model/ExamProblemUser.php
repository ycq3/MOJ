<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/6 0006
 * Time: 17:52
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class ExamProblemUser extends Model
{
//    protected $appends = ['title'];

    public function problem()
    {
        return $this->exam_problem();
    }

    public function exam_problem()
    {
        return $this->belongsTo(ExamProblem::class);
    }

    public function submit()
    {
        return $this->morphToMany(ExamProblemSubmit::class);
    }
//    public function getTitleAttribute()
//    {
//        return $this->problem->title;
//    }
}