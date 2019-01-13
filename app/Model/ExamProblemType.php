<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-31
 * Time: 下午1:31
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class ExamProblemType extends Model
{
    protected $fillable=['name'];
    public $timestamps = false;

    public function exam_problem()
    {
        return $this->belongsToMany(ExamProblem::class);
    }

    public function problem()
    {
        return $this->exam_problem();
    }
}
