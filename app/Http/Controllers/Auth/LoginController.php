<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ResponseHelpers;
use App\Http\Controllers\Traits\ProxyHelpers;
use App\Http\Requests\LoginRequest;
use App\Services\PassportServices;
use App\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use ResponseHelpers;
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = '/';

    private $passportServices;

    /**
     * LoginController constructor.
     * @param PassportServices $passportServices
     */
    public function __construct(PassportServices $passportServices)
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth:api')->only('logout');
        $this->passportServices=$passportServices;
        $this->middleware('throttle:60,1');
    }

    /**
     * Override login method ,use OAuth for user login
     * @param LoginRequest $request login data
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response login result
     */
    public function login(LoginRequest $request)
    {
        try{
            $tokens=$this->passportServices->login($request->email,$request->password);
            return $tokens;
        }catch (UnauthorizedException $unauthorizedException){
            return $this->failed(['email'=>$unauthorizedException->getMessage()]);
        }catch (ClientException $clientException){
            return $this->failed(['password'=>'密码错误']);
        }
    }

    /**
     * Override logout method,clean user login token.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        return $this->passportServices->logout();
    }
}
