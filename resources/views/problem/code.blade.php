<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-5-28
 * Time: 下午10:32
 */ ?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div>运行ID： {{$runid}}</div>
        <div>用户名： {{$user}}</div>
        <div>运行时间： {{$time}}</div>
        <div>运行内存： {{$mem}}</div>
        <div>语言： {{$language}}</div>
        <div>运行结果： {{$result}}</div>
        <div>提交时间： {{$submit_time}}</div>
        <div>用户代码： </div>
        <textarea cols="120" rows="20">
            {{$code}}
        </textarea>
        <div>错误信息： </div>
        <textarea cols="120" rows="20">
            {{$info}}
        </textarea>
        <br/>
        @foreach($pass_detail as $i)
            {{$i}}<br/>
        @endforeach
    </div>
@endsection
