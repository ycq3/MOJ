<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/21 0021
 * Time: 15:49
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use function is_array;
use function json_decode;
use function json_encode;

class Written extends Model
{
    public static $ExclusiveChoice = 1; //单选
    public static $FillBlank = 2; //填空
    public static $MultipleChoice = 3; //多选
    public static $ShortAnswer=4; //简答

    //[1 => '选择', 2 => '填空', 3 => '多选', 4 => '简答']
    public function getContentAttribute()
    {
        switch ($this->type) {
            case 1:
            case 3:
                $value = json_decode($this->attributes['content']);
                return $value ? $value : [];
            case 2:
            case 4:
                return $this->attributes['content'];
        }
    }

    public function setContentAttribute($value)
    {
        if (is_array($value)) {
            $value=array_filter($value);
            $this->attributes['content'] = json_encode($value);
        } else {
            $this->attributes['content'] = $value;
        }
    }

    public function written_set()
    {
        return $this->belongsToMany(WrittenSet::class);
    }

}