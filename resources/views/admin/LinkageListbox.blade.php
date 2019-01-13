<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
        @include('admin::form.help-block')
        <div class="form-group ">
            <select class="form-control {{$class}}_select" style="width: 100%;" {!! $attributes !!} >
                <option value="-1">全部</option>
                @foreach($options as $select => $option)
                    <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group ">
            <select class="form-control {{$class}}_list" style="width: 100%;" {!! $attributes !!} >
            </select>
        </div>

        <div class="form-group ">
            <div class="btn btn-success {{$class}}_btn">添加</div>
        </div>

        <div class="form-group ">
            <select class="form-control {{$class}}" style="width: 100%;" multiple="multiple" size="10"
                    name="{{$name}}[]" data-placeholder="{{ $placeholder }}">
                @if($value)
                @foreach($value as $select => $option)
                    <option value="{{$option}}" selected>{{$option}}</option>
                @endforeach
                    @endif
            </select>
            <input type="hidden" name="{{$name}}[]"/>
        </div>

    </div>
</div>
<script type="text/javascript">

</script>