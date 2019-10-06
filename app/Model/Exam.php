<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-29
 * Time: 下午1:31
 */

namespace App\Model;




use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
//
    public static $PUBIC = 0;
    public static $PRIVATE = 1;
    public static $CLASS=2;
    public static $INSIDE=3;

    public static $Rank_on=0;
    public static $Rank_off=1;
    public static $Rank_limit=2;

    public static $Correct_on=0;
    public static $Correct_off=1;
    public static $Correct_running=2;
    public static $Correct_done=3;


    protected $hidden = [
        'password', 'classes','updated_at','created_at'
    ];
    public function exam_problem_type()
    {
        return $this->belongsToMany(ExamProblemType::class);
    }

    public function problem_type()
    {
        return $this->exam_problem_type();
    }

    public function getClassesAttribute($value)
    {
        return explode(',', $value);
    }

    public function setClassesAttribute($value)
    {
        $this->attributes['classes']=implode(",", $value);
    }

    public function getWrittenSetAttribute($value)
    {
        return explode(',', $value);
    }

    public function setWrittenSetAttribute($value)
    {
        $this->attributes['written_set']=implode(",", $value);
    }
}
