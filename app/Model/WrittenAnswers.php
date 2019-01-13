<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/28 0028
 * Time: 16:17
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class WrittenAnswers extends Model
{
    public function written()
    {
        return $this->belongsTo(Written::class);
    }
}