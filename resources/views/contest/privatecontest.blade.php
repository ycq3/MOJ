<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-3
 * Time: 上午10:23
 */
?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header text-center h2">
                请输入密码
            </div>
            <div class="card-body">
                <form method="post" action="{{route('contest_main',$id)}}">
                    @csrf
                    <input type="password" name="password">
                    <input type="submit">
                </form>
            </div>
        </div>
    </div>
@endsection


