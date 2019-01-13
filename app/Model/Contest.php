<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-29
 * Time: 下午1:31
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    //
    public static $PUBIC = 0;
    public static $PRIVATE = 1;
    public static $CLASS=2;
    protected $hidden = [
        'password', 'classes','updated_at','created_at'
    ];

    public function problems()
    {
        return $this->belongsToMany(Problem::class);
    }

    public function getClassesAttribute($value)
    {
        return explode(',', $value);
    }

    public function setClassesAttribute($value)
    {
        $this->attributes['classes']=implode(",", $value);
    }
}
