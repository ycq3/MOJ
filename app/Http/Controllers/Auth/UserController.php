<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/8 0008
 * Time: 15:35
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Services\PassportServices;

class UserController extends Controller
{
    private $passportServices;
    public function __construct(PassportServices $passportServices)
    {
        $this->passportServices=$passportServices;
        $this->middleware('auth:api');
    }

    public function getDetails()
    {
        return $this->passportServices->getDetails();
    }

}