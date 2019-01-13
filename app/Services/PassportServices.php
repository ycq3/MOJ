<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/7 0007
 * Time: 19:45
 */

namespace App\Services;


use App\Http\Controllers\Traits\Censor;
use App\Http\Controllers\Traits\ResponseHelpers;
use App\Http\Controllers\Traits\ProxyHelpers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\User;
use function event;
use function GuzzleHttp\Promise\exception_for;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;
use Jrean\UserVerification\Facades\UserVerification;
use Mockery\Exception;

class PassportServices
{
    use ProxyHelpers,ResponseHelpers,Censor;

    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login($email, $password)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new UnauthorizedException('此用户不存在');
        }
        if ($user)
            try {
                $user->AauthAcessToken()->delete();
            } catch (Exception $e) {
                return $this->failed('Internal Server Error (code:PassportServices Error)');
            }

        $tokens = $this->authenticate($email, $password);

        return $this->succeed(['token' => $tokens, 'user' => $user]);
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if(Auth::check()){
            Auth::user()->AauthAcessToken()->delete();
            return $this->succeed('logouted');
        }
        return $this->failed('You were not login');
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Register api
     *
     * @param $name
     * @param $email
     * @param $password
     * @param $sex
     * @param $quote
     * @return \Illuminate\Http\Response
     */
    public function register($name, $email, $password, $sex, $quote)
    {
        try {
            if(strpos($email,'@mnnu')){
                return $this->failed(['username'=>'专用邮箱不允许注册']);
            }

            if(User::where('email',$email)->get()->count()){
                return $this->failed(['username' => '邮箱已被使用']);
            }

            if(!$this->text_censor($name)){
                return $this->failed(['nickname' => '注册信息中包含非法内容！']);
            }

            if(!$this->text_censor($quote)){
                return $this->failed(['quote' => '注册信息中包含非法内容！']);
            }

            $user = $this->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
            ]);
            if ($user) {
                event(new Registered($user));
                $user->information()->create([
                    'sex' => $sex,
                    'quote' => $quote
                ]);
                return $this->login($email, $password);
            }
        } catch (QueryException $exception){
            return $this->failed(['username' => '邮箱已被使用']);
        }
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetails()
    {
        $user = Auth::user();
        return $this->succeed(['user' => $user]);
    }

    public function update($email,$o_password,$n_password,$name,$sex,$quote)
    {
        $user = Auth::user();
        if (!Hash::check($o_password, $user->password)&&$user->email==$email) {
            return $this->failed('Request Error (code:PassportServices Error)');
        }
        if ($n_password >= 6) {
            $user->password = Hash::make($n_password);
            $user->setRememberToken(Str::random(60));
            $this->logout();
        }
        $user->name=$name;
        $user->sex=$sex;
        $user->quote=$quote;
        event(new PasswordReset($user));
        $user->save();
        return $this->succeed('信息修改成功');
    }
}