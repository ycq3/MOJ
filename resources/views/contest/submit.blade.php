<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-5-28
 * Time: 下午1:16
 */
?>

@extends('layouts.app')

@section('content')
    <div class="container">
        <div>用户名：{{Auth::user()->name}}</div>
        <div>考试ID:{{$cid}}</div>
        <div>问题ID:{{$id}}</div>
        <div>
            <form action="{{route('contest_codesubmit',[$cid,$id])}}" method="post">
                @csrf
            <div>语言:
                <select class="form-control" name="language">
                    @foreach($language as $i=>$j)
                        <option value="{{$i}}">{{$j}}</option>
                    @endforeach
                </select>
            </div>
                <textarea name="code" rows="15" cols="100" class="input-lg">
                </textarea>
                <input type="submit">
            </form>
        </div>
    </div>
@endsection
