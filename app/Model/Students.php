<?php
/**
 * Created by PhpStorm.
 * User: ycqyc
 * Date: 2018/12/10
 * Time: 19:52
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    public $timestamps=false;
    public function classes()
    {
        return $this->belongsTo(Classes::class);
    }
}