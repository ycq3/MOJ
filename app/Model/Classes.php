<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-29
 * Time: 下午3:06
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table='class';
    public $timestamps=false;
    public function students()
    {
        return $this->hasMany(Students::class,'class_id');
    }
}
