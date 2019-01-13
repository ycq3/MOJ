<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-5-25
 * Time: 下午5:29
 */
?>
@extends('layouts.app')

@section('content')
    <script type="text/javascript">
        new Vue(
            document.title="Problem"
        );
    </script>
    <div class="container">
        <div class="card">
            <h2 class="card-header text-center">{{$title}}</h2>
            <div class="card-body">

                <div class="alert alert-info text-center">
                    时间限制: {{$time}} | {{$other_time}} 内存限制: {{$memory}} | {{$other_memory}}
                </div>

                <div class="card">
                    <div class="card-header">问题描述</div>
                    <div class="card-body">{!! $describe !!}</div>
                </div>

                <div class="card">
                    <div class="card-header">输入描述</div>
                    <div class="card-body">{!!$input!!}</div>
                </div>

                <div class="card">
                    <div class="card-header">输出描述</div>
                    <div class="card-body">{!!$output!!}</div>
                </div>

                <div class="card">
                    <div class="card-header">样例输入</div>
                    <div class="card-body">{!!$sampleinput!!}</div>
                </div>

                <div class="card">
                    <div class="card-header">样例输出</div>
                    <div class="card-body">{!!$sampleoutput!!}</div>
                </div>

                <div class="card">
                    <div class="card-header">提示</div>
                    <div class="card-body">{!!$hint!!}</div>
                </div>

            </div>
            <div class="card-footer text-center">
                <a href="{{route('submit',$id)}}" class="btn btn-success">提交</a>

                <a href="{{route('status',$id)}}" class="btn btn-warning">评测状态</a>
            </div>
        </div>
@endsection
