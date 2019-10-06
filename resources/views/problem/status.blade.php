<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-5-28
 * Time: 下午7:17
 */ ?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table table-hover">
            <tr>
                @foreach($table_header as $i)
                    <th>
                        {{$i}}
                    </th>
                @endforeach
            </tr>

            @foreach($table_data as $i)
                <tr>
                    <td>{{$i['runid']}}</td>
                    <td>{{$i['pid']}}</td>
                    <td>{{$i['user']}}</td>
                    <td>{{$i['time']}}</td>
                    <td>{{$i['mem']}}</td>
                    @if(Auth::user()->id==$userid)
                        <td><a href="{{route('codeshow',$i['runid'])}}">{{$i['code_len']}}</a></td>
                    @else
                        <td>{{$i['code_len']}}</td>
                    @endif
                    <td>{{$i['language']}}</td>
                    <td>{{$i['result']}}</td>
                    <td>{{$i['submit_time']}}</td>
                </tr>
            @endforeach
        </table>
        {{$paginator}}
    </div>
@endsection
