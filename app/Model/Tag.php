<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //关闭时间戳
    public $timestamps = false;
    //关联问题表，可以通过标签获取问题的Collection
    public function problem()
    {
        return $this->belongsToMany(Problem::class);
    }
}
