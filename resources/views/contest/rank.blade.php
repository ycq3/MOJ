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
        Rank更新时间{{$rank_time}}
        <table class="table table-sm">
            <tr>
                <th>Rank</th>
                <th>Nickname</th>
                <th>Accept</th>
                <th>Penalty</th>
                @foreach($problem_id as $i)
                    <th>{{$i}}</th>
                @endforeach
            </tr>
            @foreach($rank as $row)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$row->user_name}}</td>
                <td>{{$row->accept}}</td>
                <td>{{$row->penalty}}</td>
                @foreach($row->problem_data as $col)
                    <td>{{$col['result']}}<br/>{{$col['time']}}<br/>(-{{$col['fail']}})</td>
                @endforeach
            </tr>
            @endforeach
        </table>
    </div>
@endsection
