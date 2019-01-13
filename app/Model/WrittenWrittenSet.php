<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/28 0028
 * Time: 15:52
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class WrittenWrittenSet extends Model
{
    protected $table='written_written_set';
    public function written()
    {
        return $this->belongsTo(Written::class);
    }

    public function written_set()
    {
        return $this->belongsTo(WrittenSet::class);
    }
}