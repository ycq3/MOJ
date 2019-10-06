<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/1 0001
 * Time: 11:55
 */

namespace App\Model;


use App\User;
use Illuminate\Database\Eloquent\Model;

class ExamProblemSubmit extends Model
{
    protected $fillable = ['memory', 'time', 'result', 'pass_rate', 'fetch', 'pass_detail'];

    public function getPassDetailAttribute()
    {
        return array_values(array_except(explode(',',$this->attributes['pass_detail']),0));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }


    public function problem()
    {
        return $this->belongsTo(ExamProblem::class);
    }
}