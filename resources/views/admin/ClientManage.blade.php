<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/18 0018
 * Time: 23:05
 */ ?>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="box">
    <div class="box-header">

        <h3 class="box-title"></h3>

        <div class="pull-right">
            <button class="btn btn-success" data-toggle="modal" data-target="#Modal">批量操作</button>
        </div>

        <span>
            客户端设定
        </span>

    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tr>
                @foreach($headers as $header)
                    <th>{{$header}}</th>
                @endforeach
            </tr>
            @foreach($rows as $row)
                <tr>
                    @foreach($row as $item)
                        <td>
                            {{$item}}
                        </td>
                    @endforeach
                    <td>
                        <button class="btn btn-primary" onclick="client_manage({{$row['cid']}})">操作</button>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <!-- /.box-body -->
</div>

<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">客户端批量管理</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="op_type">操作类型:</label>
                    <select class="form-control" id="op_type" onchange="type_change($(this))">
                        <option value="message">发送消息</option>
                        <option value="reload">执行刷新</option>
                        <option value="goto">转到指定页面</option>
                        <option value="dialog">显示内容</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="range">客户端范围:</label>
                    <select class="form-control" id="range" onchange="range_change($(this))">
                        <option value="all">所有客户端</option>
                        <option value="exam">考试中客户端</option>
                        <option value="room">指定考场客户端</option>
                        <option value="user">指定用户ID客户端</option>
                        <option value="uid">指定UID客户端</option>
                    </select>
                </div>
                <div class="form-group" id="range_input" hidden>
                    <label for="to" id="range_input_lab"></label>
                    <input type="text" class="form-control" id="to">
                </div>
                <div class="form-group" id="content_input">
                    <label for="content" id="range_input_lab">内容</label>
                    <input type="text" class="form-control" id="content">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" onclick="send()">提交</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>


<script>
    function range_change(self) {
        switch (self.find("option:selected").val()) {
            case 'all':
                $('#range_input').hide();
                break;
            case 'exam':
                $('#range_input').show();
                $('#range_input_lab').html('考试ID');
                break;
            case 'room':
                $('#range_input').show();
                $('#range_input_lab').html('考场ID');
                break;
            case 'user':
                $('#range_input').show();
                $('#range_input_lab').html('用户ID');
                break;
            case 'uid':
                $('#range_input').show();
                $('#range_input_lab').html('客户端ID');
                break;
        }
    }

    function type_change(self) {
        switch (self.find("option:selected").val()) {
            case'reload':
                $('#content_input').hide();
                break;
            case 'message':
                $('#content_input').show();
                break;
            case 'reload':
                $('#content_input').hide();
                break;
            case 'goto':
                $('#content_input').show();
        }
    }

    function client_manage(cid) {
        $('#range_input').show();
        $('#range').val('uid');
        $('#range_input_lab').html('客户端ID');
        $('#range_input').children('input').val(cid);
        $('#Modal').modal('show');
    }

    function send() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let content, type;
        switch ($('#op_type').find("option:selected").val()) {
            case'reload':
                content = {'reload': ''};
                type = 'reload';
                break;
            case 'message':
                type='message';
                content = {'message': $('#content').val()};
                break;
            case'goto':
                type='goto';
                content={'goto': $('#content').val()};
                break;
            case 'dialog':
                type='dialog';
                content={'dialog': $('#content').val()};
                break;
        }

        $.post('/admin/web_socket/socket', {
            'to': $('#to').val(),
            'type': type,
            'content': JSON.stringify(content)
        }, function (resp) {
            if(resp!=='ok'){
                toastr.error(resp);
            }else{
                toastr.success(resp);
                $('#Modal').modal('hide');
            }
        });
    }
</script>