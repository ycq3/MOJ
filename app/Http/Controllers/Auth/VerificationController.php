<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/5 0005
 * Time: 11:24
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Services\VerificationService;
use Illuminate\Support\Facades\Auth;
use function redirect;

class VerificationController extends Controller
{
    private $verificationService;
    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService=$verificationService;
        $this->middleware('auth:api');
    }

    public function send($user)
    {
        $this->verificationService->send($user);
    }

    public function resend()
    {
        $user=Auth::user();
        if($user->verified){
            return redirect('/');
        }
        $this->send($user);
    }
}