<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Sysconfig extends Model
{
    public $timestamps=false;
    protected $fillable=['status'];
}
