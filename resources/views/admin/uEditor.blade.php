<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-5
 * Time: 下午4:51
 */
?>
{{--Laravel admin uEditor 视图文件--}}
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="editor_{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
        <script id="editor_{{$id}}" class="edui-default" name="{{$name}}"  type="text/plain">{!! old($column, $value) !!}</script>
    </div>
</div>
