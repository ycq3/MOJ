<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/9/24 0024
 * Time: 14:46
 */ ?>
<div>
    <div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

        <label for="editor_{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

        <div class="{{$viewClass['field']}}">
            @include('admin::form.error')
            <div class="{{$id}}_container ">
                @if($view_type==1)
                    <div>
                        @if($value)
                        @foreach($value as $item)
                            <input type="text" name="{{$name}}[]" class="form-control " style="margin-bottom: 10px"value="{{$item}}">
                        @endforeach
                            @endif
                    </div>
                    <div class="btn btn-success {{$id}}_btn">添加</div>
                @elseif($view_type==2)

                @elseif($view_type==3)
                @elseif($view_type==4)
                    <textarea class="form-control {{$class}}" id="{{$id}}" name="{{$name}}"
                              placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>
                @elseif($view_type==5)
                @elseif($view_type==6)
                @endif
            </div>
            @include('admin::form.help-block')
        </div>
    </div>
</div>
