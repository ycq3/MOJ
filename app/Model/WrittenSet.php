<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/18 0018
 * Time: 17:31
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class WrittenSet extends Model
{
    public function writtens()
    {
        return $this->belongsToMany(Written::class);
    }
}