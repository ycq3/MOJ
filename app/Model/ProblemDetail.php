<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProblemDetail extends Model
{
    //
    public $timestamps = false;

    public function getLanguageAttribute($value)
    {
        return explode(',', $value);
    }

    public function setLanguageAttribute($value)
    {
        $this->attributes['language']=implode(",", $value);
    }
}
