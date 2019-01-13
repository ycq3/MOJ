<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Model;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    protected $fillable=['school','sex','quote'];

    public function getSubmittedAttribute()
    {
        if($this->updated_at==null||Carbon::parse($this->updated_at)->addSecond(60)->lt(Carbon::now())){
            $submitted=ProblemSubmit::where('user_id',$this->user_id)->count();
            $this->submitted=$submitted;
            $this->save();
            return $submitted;
        }else{
            return $this->attributes['submitted'];
        }
    }

    public function getAcceptedAttribute()
    {
        if($this->updated_at==null||Carbon::parse($this->updated_at)->addSecond(60)->lt(Carbon::now())){
            $ac_count=ProblemSubmit::where([['user_id',$this->user_id],['result',RunResult::$OJ_AC]])->select('problem_id')->distinct()->get()->count();
            $this->accepted=$ac_count;
            $this->save();
            return $ac_count;
        }else{
            return $this->attributes['accepted'];
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
