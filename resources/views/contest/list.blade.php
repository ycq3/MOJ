<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-4
 * Time: 下午8:55
 */ ?>

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header text-center h2">
                Problem
                <List></List>
            </div>
            <div class="card-body">

                <table class="table">
                    @foreach($problem_list as $i)

                        <tr>
                            <td>
                                {{$loop->iteration}}
                            </td>
                            <td>
                                <a href="{{route('contest_problem',[$contest_id,$i['id']])}}">
                                    {{$i['title']}}
                                </a>
                            </td>
                        </tr>

                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
