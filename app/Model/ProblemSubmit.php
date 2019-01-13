<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Model;

use App\User;
use function array_values;
use Illuminate\Database\Eloquent\Model;

class ProblemSubmit extends Model
{
    //
    protected $fillable = ['memory', 'time', 'result', 'pass_rate', 'fetch', 'pass_detail'];

    public function getPassDetailAttribute()
    {
        return array_values(array_except(explode(',',$this->attributes['pass_detail']),0));
    }

    /*public function setPassDetailAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['pass_detail'] = implode("/", $value);
        } else {
            $this->attributes['pass_detail'] = $value;
        }
    }*/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function getFakeIdAttribute()
    {
        if ($this->contest_id!=null){
            return ContestProblem::where([
                ['contest_id',$this->contest_id],
                ['problem_id',$this->problem_id],
            ])->first()->fake;
        }else{
            return $this->problem_id;
        }
    }

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }
}
