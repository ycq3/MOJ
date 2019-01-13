<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Traits\ResponseHelpers;
use App\Http\Requests\RegisterRequest;
use App\Services\PassportServices;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use Mockery\Exception;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    private $passportServices;
    use ResponseHelpers;
    use RegistersUsers;

    use VerifiesUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @param PassportServices $passportServices
     */
    public function __construct(PassportServices $passportServices)
    {
        $this->middleware('guest', [' except' => ['getVerification', 'getVerificationError']]);
        $this->passportServices = $passportServices;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        try{
            $token=$this->passportServices->register($request->name,$request->email,$request->password,$request->sex,$request->quote);
        }catch (Exception $exception){
            return $this->failed(['email'=>$exception->getMessage()]);
        }
        return $token;
    }
}
