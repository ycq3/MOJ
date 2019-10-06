<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-29
 * Time: 下午1:31
 */

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContestProblem extends Model
{

    //添加自定义数据
    protected $appends = ['ac_flag'];
    //public $timestamps = false;
    protected $table = 'contest_problem';
    protected $fillable = ['fake'];
    protected $hidden = ['problem_id'];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }

    //屏蔽系统更新数据库操作
    public function update(array $attributes = [], array $options = [])
    {
        return true;
    }

    //混淆题目编号 阴险的笑笑
    public function setFake($id)
    {
        DB::update('update contest_problem set fake=? where contest_id=? and problem_id=?', [$id, $this->contest_id, $this->problem_id]);
    }

    public function getSubmitedAttribute()
    {
        if ($this->updated_at == null || Carbon::parse($this->updated_at)->addSecond(60)->lt(Carbon::now())) {
            $submit_count = ProblemSubmit::where([['problem_id', $this->problem_id], ['contest_id', $this->contest_id]])->count();
            $this->submited = $submit_count;
            $this->save();
            return $submit_count;
        } else {
            return $this->attributes['submited'];
        }
    }

    public function getAcceptedAttribute()
    {
        if ($this->updated_at == null || Carbon::parse($this->updated_at)->addSecond(60)->lt(Carbon::now())) {
            $ac_count = ProblemSubmit::where([['problem_id', $this->problem_id], ['result', RunResult::$OJ_AC], ['contest_id', $this->contest_id]])->count();
            $this->accepted = $ac_count;
            $this->save();
            return $ac_count;
        } else {
            return $this->attributes['accepted'];
        }
    }

    public function getAcFlagAttribute()
    {
        $user = Auth::user();
        if ($user) {
            return ProblemSubmit::where([['problem_id',$this->problem_id],['result',RunResult::$OJ_AC],['user_id',$user->id],['contest_id',$this->contest_id]])->count()!=0;
        } else {
            return false;
        }
    }
}
