<?php

use Illuminate\Routing\Router;


Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->resource('user', UserController::class);//用户管理
    $router->resource('problem',ProblemController::class);//问题管理
    $router->get('problem/check_testcase/{id}','ProblemController@check_testcase')->name('problem.check_testcase');
    $router->resource('problem_tag',ProblemTagController::class);//问题分类管理
    $router->resource('contest',ContestController::class);//比赛管理
    $router->resource('exam',ExamController::class);//考试管理
    $router->resource('exam_problem_tag',ExamProblemTagController::class);//考试上机题目分类管理
    $router->resource('exam_problem',ExamProblemController::class);//考试上机题目管理
    $router->resource('exam_written',ExamWrittenController::class);//考试笔试题目管理
    $router->resource('exam_written_set',ExamWrittenSetController::class);//考试题目分类管理
    $router->resource('page',PageController::class);//页面管理
    $router->resource('sysconfig',SysController::class);//系统配置,模块管理
    $router->resource('judge',JudgeController::class);//评测机管理
    $router->resource('class',ClassController::class);//班级管理


    $router->post('judge/rejudge', 'JudgeController@rejudge');//Rejudge接口
    $router->get('web_socket','WebClientController@index');//客户端管理接口
    $router->post('web_socket/socket','WebClientController@socket');//客户端管理指令发送接口

});


Route::group([
    'prefix'        => config('admin.route.prefix').'/api',
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('problem','ContestController@api_problem');
});
