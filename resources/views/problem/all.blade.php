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
        <div class="row">

            <div class="col-lg-8">
                <div class="card">
                    <table class="table table-hover">
                        <tr>
                            @foreach($table_header as $th)
                                <th>
                                    {{$th}}
                                </th>
                            @endforeach
                        </tr>

                        @foreach($table_data as $i)
                            <tr>
                                <td>{{$i['id']}}</td>
                                <td><a href="{{route('problemshow',$i['id'])}}">{{$i['title']}}</a></td>
                                <td>{{$i['submited']}}</td>
                                <td>{{$i['difficult']}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        搜索
                    </div>
                    <div class="card-body">
                        <form method="get" action="{{route('problem')}}">
                            <input type="text" name="keyword">
                            <input type="submit" value="搜索">
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        问题分类
                    </div>
                    <div class="card-body">
                        <div class="btn-group-toggle">
                            <form method="get" action="{{route('problem')}}">
                                @foreach($tags as $tag)
                                    <input name="tag[]" type="checkbox" class="checkbox" value="{{$tag['id']}}" />
                                    {{$tag['tag_name']}}
                                @endforeach
                                <input type="submit" value="确定">
                            </form>

                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div>
            {{$paginator}}
        </div>
    </div>
@endsection
