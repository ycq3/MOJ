<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ResponseHelpers;
use App\Http\Requests\UpdateInfoRequest;
use App\Services\PassportServices;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery\Exception;
use Qiniu\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    use ResponseHelpers;
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $passportServices;

    /**
     * Create a new controller instance.
     *
     * @param PassportServices $passportServices
     */
    public function __construct(PassportServices $passportServices)
    {
        $this->passportServices=$passportServices;
        $this->middleware('guest')->except('updateInformation');
        $this->middleware('auth:api')->only('updateInformation');
    }

    public function updateInformation(UpdateInfoRequest $request)
    {
        $o_password = $request->o_password;
        $n_password = $request->n_password;
        $name=$request->name;
        $quote=$request->quote;
        $sex=$request->sex;
        $email=$request->email;
        try{
            return $this->passportServices->update($email,$o_password,$n_password,$name,$sex,$quote);
        }catch (Exception $e){
            return $this->failed($e->getMessage());
        }
    }
}
