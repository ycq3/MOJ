<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProblemTag extends Model
{
    protected $table='problem_tag';

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
