<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 18-7-27
 * Time: 上午11:39
 *
 * 自己造轮子？算了，不打算写了，有缘再说
 */
?>
<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <input type="file" class="{{$class}}" name="{{$name}}" {!! $attributes !!} />

        @include('admin::form.help-block')

    </div>
</div>
