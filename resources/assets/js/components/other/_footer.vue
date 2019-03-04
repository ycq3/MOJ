<template>
    <footer class="container">
        <br>
        <div>
            <small>Minnan Normal University Online Judge System 2.0 Beta</small>
            <p>闽ICP备18023108号-1</p>
        </div>
        <div>
            <small><a href="https://github.com/ycq3/MOJ">Power By MOJ Project</a></small>
        </div>
        <br>

        <!--注册提示框-->
        <div class="modal fade" id="resignHint"  role="dialog" aria-labelledby="resignHint"
             aria-hidden="true" style="top: 25%">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">

                    <div class="modal-header">
                        <span class="modal-title">系统消息</span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body text-danger">
                        若未收到激活邮件，请点击用户下拉框的验证邮箱重新发送激活邮件！
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>

                </div>
            </div>
        </div>

        <!--发送重置密码邮件提示框-->
        <div class="modal fade" id="forgetPassword"  role="dialog" aria-labelledby="forgetPassword"
             aria-hidden="true" style="top: 25%">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">

                    <div class="modal-header">
                        <span class="modal-title">系统消息</span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body text-danger">
                        <button class="btn btn-primary" v-on:click="sendPasswordEmail">发送重置密码邮件</button>
                    </div>

                </div>
            </div>
        </div>

    </footer>

</template>

<script>
    export default {
        name: "_foot",
        data() {
            return {
                token: '',
                ws:null,
            }
        },
        mounted(){
          this.cheat();
        },
        methods: {
            cheat:function () {
                let ws = new WebSocket("wss://direct-oj.pipiqiang.cn:9130");
                // let ws = new WebSocket("ws://localhost:9130");
                ws.onopen = function() {
                    self.setInterval(()=>{
                        let data={'type':'1'};
                        this.ws.send(JSON.stringify(data));
                    },3000);
                    this.ws=ws;
                    Notification.requestPermission();
                };
                ws.onmessage =(e)=>{
                    let data=JSON.parse(e.data);
                    if(data['message']!=null&&data['message']!='connect'){//忽略心跳包
                        this.$toastr.s(data['message']);
                    }else if(data['reload']!=null){
                        console.log('reload');
                        window.location.reload();
                    }else if(data['goto']!=null){
                        window.location.href=data['goto'];
                    }else if(data['dialog']!=null){
                        Notification.requestPermission(function (perm) {
                            if (perm === "granted") {
                                var notification = new Notification("系统通知:", {
                                    dir: "auto",
                                    lang: "hi",
                                    tag: "testTag",
                                    icon: "image/oj_logo.png",
                                    body: data['dialog'],
                                });
                            }
                        })
                    }
                };
                window.ws=ws;
            },
            sendPasswordEmail(){
                this.$toastr.s("邮件发送成功！");
                //this.$toastr.e("邮件发送失败！");
            }
        }
    }
</script>

<style scoped>

</style>
