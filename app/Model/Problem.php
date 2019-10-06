<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Problem extends Model
{

    //添加自定义数据
    protected $appends = ['ac_flag'];
    //设置可填充字段
    protected $fillable = ['title', 'submited', 'difficulty', 'accepted'];
    //关闭时间戳
//    public $timestamps = false;

    public function create_detail()
    {
        $this->detail()->create([
                'id' => $this->id,
                'title' => $this->title
            ]);
    }

    //一个问题对应多个TestCase
    public function test_cases()
    {
        return $this->hasMany(ProblemTestcase::class);
    }

    public function detail()
    {
        return $this->hasOne(ProblemDetail::class, 'id');
    }

    public function tag()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title']=$value;
    }

    public function getSubmitedAttribute()
    {
        if ($this->updated_at == null || Carbon::parse($this->updated_at)->addSecond(60)->lt(Carbon::now())) {
            $submit_count = ProblemSubmit::where('problem_id', $this->id)->count();
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
            $ac_count = ProblemSubmit::where([['problem_id', $this->id], ['result', RunResult::$OJ_AC]])->count();
            $this->accepted = $ac_count;
            $this->save();
            return $ac_count;
        } else {
            return $this->attributes['accepted'];
        }

    }

    public function getAcFlagAttribute()
    {
        $user = auth('api')->user();
        if ($user) {
            return ProblemSubmit::where([['problem_id', $this->id], ['result', RunResult::$OJ_AC], ['user_id', $user->id]])->count() != 0;
        } else {
            return false;
        }
    }
}
