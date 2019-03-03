<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;

class ProblemTestcase extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    public $primaryKey = 'testcase_id';
    protected $fillable = ['input', 'output', 'score'];
    protected $appends = ['input_file_name','output_file_name'];

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }

    public function getOutputFileNameAttribute()
    {
        return @explode('/',$this->output)[3];
    }

    public function getInputFileNameAttribute()
    {
        return @explode('/',$this->input)[3];
    }
}
