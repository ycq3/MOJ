<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Exam\WrittenController;
use App\Http\Controllers\Problem\ProblemController;
use App\Model\Exam;
use App\Model\ExamProblemSubmit;
use App\Model\ExamProblemUser;
use App\Model\Problem;
use App\Model\ProblemSubmit;
use App\Model\RunResult;
use App\Model\Written;
use App\Model\WrittenAnswers;
use App\Model\WrittenWrittenSet;
use App\Services\SystemCensorService;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Monolog\Logger;

Route::get('/test', function (\Illuminate\Http\Request $request) {
    $email='1123123@2.com';
    dump( User::where('email',$email)->get());
});
Route::get('/', function () {
    return view('welcome');
});
Route::get('/error', function () {
    echo '错误';
})->name('error');

Route::post('get_details', 'Auth\UserController@getDetails');
Route::post('passport/update_information', 'Auth\ResetPasswordController@updateInformation');

Route::group(['middleware' => ['web', 'module']], function () {
    Route:: post('/login', 'Auth\LoginController@login')->name('login');
    Route:: post('/logout', 'Auth\LoginController@logout');
    Route:: post('/register', 'Auth\RegisterController@register')->name('register');
    Route::post('/verify', 'Auth\VerificationController@resend');
});


Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['web', 'module']], function () {
    Route::group(['prefix' => 'problem', 'namespace' => 'Problem'], function () {
        Route::post('/', 'ProblemController@index')->name('problem');
        Route::post('/tags', 'ProblemController@tags')->name('tags');
        Route::post('/id/{id}', 'ProblemController@show')->where(['id' => '[0-9]+'])->name('problemshow');
        //需要登录才能进行
        Route::group(['middleware' => ['auth:api']], function () {
            //提交代码
            //Route::get('/submit/{pid}', 'ProblemController@submit')->where(['pid' => '[0-9]+'])->name('submit');
            //接收代码
            Route::post('/submit/{pid}', 'ProblemController@code_submit')->where(['pid' => '[0-9]+'])->name('codesubmit');
            //查看代码 cid=代码编号
            Route::post('/code_show/{sid}', 'ProblemController@code_show')->where(['sid' => '[0-9]+'])->name('codeshow');
            //显示问题状态
            Route::post('/status', 'ProblemController@status')->name('status');
            Route::post('/status/{p_id}', 'ProblemController@status')->where(['id' => '[0-9]+']);
        });
    });

    Route::group(['prefix' => 'contest', 'namespace' => 'Contest'], function () {
        Route::post('/', 'ContestController@index')->name('contest');

        Route::post('/check/{cid}', 'ContestController@check')->where(['cid' => '[0-9]+'])->middleware(['auth:api', 'isVerified']);
        Route::post('/show/{cid}', 'ContestController@show')->where(['cid' => '[0-9]+'])->name('contest_main')->middleware(['auth:api', 'isVerified']);
        Route::group(['middleware' => ['auth:api', 'contest', 'isVerified']], function () {
            Route::post('/show/{cid}/problem/{pid}', 'ContestController@problem')->where(['cid' => '[0-9]+', 'pid' => '[0-9]+'])->name('contest_problem');
            Route::post('/show/{cid}/list', 'ContestController@list')->where(['cid' => '[0-9]+', 'pid' => '[0-9]+'])->name('contest_list');
            //提交代码
            //Route::get('/submit/{cid}/problem/{pid}', 'ContestController@submit')->where(['cid' => '[0-9]+', 'pid' => '[0-9]+'])->name('contest_submit');
            //接收代码
            Route::post('/submit/{cid}/problem/{pid}', 'ContestController@code_submit')->where(['cid' => '[0-9]+', 'pid' => '[0-9]+'])->name('contest_codesubmit');
            //查看代码 cid=代码编号
            Route::get('/codeshow/{cid}/problem/{pid}', 'ContestController@code_show')->where(['cid' => '[0-9]+', 'pid' => '[0-9]+'])->name('contest_codeshow');
            //查看状态
            Route::post('/status/{cid}/problem/{pid}', 'ContestController@status')->where(['cid' => '[0-9]+', 'pid' => '[0-9]+'])->name('contest_status');
            Route::post('/status/{cid}', 'ContestController@status_all')->where(['cid' => '[0-9]+'])->name('contest_status_all');
            Route::post('/rank/{cid}', 'ContestController@rank')->where(['cid' => '[0-9]+'])->name('contest_rank');
        });
    });

    Route::group(['prefix' => 'exam', 'namespace' => 'Exam'], function () {
        Route::post('/', 'ExamController@index')->name('exam');
        Route::group(['middleware' => ['auth:api', 'exam', 'isVerified']], function () {
            Route::post('/show/{eid}', 'ExamController@show')->where(['eid' => '[0-9]+'])->name('exam_main');
            Route::post('/show/{eid}/problem/{pid}', 'ExamController@problem')->where(['eid' => '[0-9]+', 'pid' => '[0-9]+'])->name('exam_problem');
            Route::post('/show/{eid}/list', 'ExamController@list')->where(['eid' => '[0-9]+', 'pid' => '[0-9]+'])->name('exam_list');
            //提交代码
            //Route::get('/submit/{eid}/problem/{pid}', 'ExamController@submit')->where(['eid' => '[0-9]+', 'pid' => '[0-9]+'])->name('exam_submit');
            //接收代码
            Route::post('/submit/{eid}/problem/{pid}', 'ExamController@code_submit')->where(['eid' => '[0-9]+', 'pid' => '[0-9]+'])->name('exam_codesubmit');
            //查看代码 eid=代码编号
            Route::post('/code_show/{sid}', 'ExamController@code_show')->where(['sid' => '[0-9]+'])->name('exam_code_show');
            //查看状态
            Route::post('/status/{eid}/problem/{pid}', 'ExamController@status')->where(['eid' => '[0-9]+', 'pid' => '[0-9]+'])->name('exam_status');
            Route::post('/status/{eid}', 'ExamController@status_all')->where(['eid' => '[0-9]+'])->name('exam_status_all');
            Route::post('/rank/{eid}', 'ExamController@rank')->where(['eid' => '[0-9]+'])->name('exam_rank');
            Route::post('/written/{eid}/show', 'WrittenController@show');
            Route::post('/written/{eid}/save', 'WrittenController@save');
        });
    });

    Route::group(['prefix' => 'other', 'namespace' => 'Other'], function () {
        Route::post('authors_rank', 'AuthorsRankListController@index');
        Route::post('user_info/{uid}', 'UserInformationController@index');
    });

});

//临时用session 登录 测试！！
Route::group(['middleware' => 'web', 'prefix' => 'judge', 'namespace' => 'Judge'], function () {
    Route::any('login', 'JudgeController@login');
    Route::any('check_login', 'JudgeController@check_login');
    Route::any('get_job', 'JudgeController@get_job');
    Route::any('checkout', 'JudgeController@checkout');
    Route::any('problem', 'JudgeController@get_problem_info');
    Route::any('user_code_info', 'JudgeController@user_code_info');
    Route::any('user_code', 'JudgeController@user_code');
    Route::any('update_solution', 'JudgeController@update_solution');
    Route::any('test_data_list', 'JudgeController@test_data_list');
    Route::any('get_test_data_info', 'JudgeController@get_test_data_info');
    Route::any('get_test_data', 'JudgeController@get_test_data');
    Route::any('upload_info', 'JudgeController@upload_info');
    Route::any('user_test_data', function () {
        //预留在线编译功能
    });
    Route::any('{action}', function ($action) {
        $log = new Logger('judge');
        $log->pushHandler(new \Monolog\Handler\StreamHandler(storage_path('logs/judge.log'), Logger::INFO));
        $log->info($action);
        echo "未知路由";
    });
});
