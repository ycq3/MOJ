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
    <div class="container">
        <div class="card">
            <div class="card-header text-center h2">
                {{$title}}
            </div>
            <div class="card-body">
                开始时间{{$start_time}} 结束时间{{$end_time}}
                <a href="{{route('contest_list',$id)}}" class="btn btn-primary">进入考试</a>
                <a href="{{route('contest_rank',$id)}}" class="btn btn-warning">Rank</a>
            </div>
        </div>
    </div>
@endsection

