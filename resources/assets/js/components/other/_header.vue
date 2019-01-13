<template>
    <div class="container">
        <div id="head-bar"><!--logo-->
            <img src="../../assets/OJ_logo.png" class="col-12"/>
        </div>
        <nav class="navbar navbar-expand-md navbar-light bg-light h5">
            <!--响应式导航栏按钮-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!--导航栏-->
            <div id="navbarSupportedContent" class="collapse navbar-collapse float-left">
                <ul class="navbar-nav">
                    <li id="home" class="active nav-item">
                        <router-link to="/home" class="nav-link">{{navText.home}}</router-link>
                    </li>
                    <li id="problems" class="nav-item">
                        <router-link to="/problem" class="nav-link">{{navText.problems}}</router-link>
                    </li>
                    <li id="contests" class="nav-item">
                        <router-link to="/contest" class="nav-link">{{navText.contests}}</router-link>
                    </li>
                    <li id="exams" class="nav-item">
                        <router-link to="/exam" class="nav-link">{{navText.exams}}</router-link>
                    </li>
                </ul>
            </div>
            <ul class="navbar-nav justify-content-end float-right">
                <!--未登录-->
                <li class="nav-item float-right" v-if="isLogin==false" key="unLogin">
                    <router-link to="/register" class="float-right nav-link">{{registerText.register}}</router-link>
                    <a href="JavaScript:;" v-on:click="showLoginModal" class="float-right nav-link">{{loginText.login}}</a>

                </li>
                <!--已登录-->
                <li class="nav-item dropdown" v-else key="login">
                    <a href="JavaScript:;" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        {{loginText.nickname}}
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <router-link v-bind:to="'/userInformation/'+this.globalLoginData.userID" class="dropdown-item">{{loginText.myPage}}</router-link>
                        </li>
                        <li>
                            <router-link to="/modify" class="dropdown-item">{{loginText.updateInfo}}</router-link>
                        </li>
                        <li><router-link to="/authorsRankList" class="dropdown-item">{{loginText.authorsRankList}}</router-link></li>
                        <li v-if="this.globalLoginData.userData.verified==0"><a href="JavaScript:;" v-on:click="virify" class="dropdown-item text-warning">{{loginText.verifyEmail}}</a> </li>
                        <li><a href="JavaScript:;" v-on:click="logout" class="dropdown-item">{{loginText.loginOut}}</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!--登录弹窗 begin -->
        <div class="modal fade" id="login" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
            <div class="modal-dialog" id="login-dialog" style="max-width:440px;top:25%;">
                <div class=" modal-content">
                    <div class="modal-header col-sm-12">
                        <h4 class="modal-title container" id="loginLabel">
                            {{loginText.login}}
                        </h4>
                        <button v-on:click="loadingEnd" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                    </div>
                    <div role="form" id="login_form">
                        <div class="modal-body container">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-3 text-right control-label float-left"
                                           for="Lusername">{{loginText.username}}</label>
                                    <div class="input-group col-sm-8">
                                        <span class="input-group-addon"><span
                                            class="glyphicon glyphicon-user"></span></span>
                                        <input id="Lusername" class="form-control"
                                               v-bind:placeholder="loginText.usernamePlace" type="email" name="email"
                                               v-model="login_data.email" required>
                                        <div class="invalid-feedback">
                                            {{login_data.email_error}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-3 text-right control-label float-left" for="Lpassword">{{loginText.password}}</label>
                                    <div class="input-group col-sm-8">
                                        <span class="input-group-addon"><span
                                            class="glyphicon glyphicon-lock"></span></span>
                                        <input id="Lpassword" class="form-control"
                                               v-bind:placeholder="loginText.passwordPlace" type="password"
                                               name="password" v-model="login_data.password" required>
                                        <div class="invalid-feedback">
                                            {{login_data.password_error}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-show="loading" key="loading">
                                <img src="../../assets/loading.gif"/> <small class="text-success">登陆中......</small>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button id="loginbtn" class="btn btn-primary" v-on:click="login">{{loginText.login}}
                            </button>
                            <button v-on:click="loadingEnd" class="btn btn-default" data-dismiss="modal">{{loginText.close}}
                            </button>
                            <router-link to="/register" data-dismiss="modal">{{loginText.register}}</router-link>
                            <a href="JavaScript:;" v-on:click="forgetPassword">{{loginText.forget}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--登录弹窗 end-->

    </div>
</template>

<script>
    export default {
        name: "_head",
        data() {
            return {
                navText: {
                    home: '主页',
                    problems: '问题',
                    contests: '比赛',
                    exams: '考试',
                },
                loginText: {
                    login: '登录',
                    username: '邮箱',
                    password: '密 码',
                    userlogin: '用户登录',
                    close: '关闭',
                    forget: '忘记密码？',
                    register: '立即注册',
                    nickname: '用户1',
                    myPage: '我的主页',
                    updateInfo: '更改信息',
                    authorsRankList: '总榜',
                    verifyEmail:'验证邮箱',
                    loginOut: '退出',
                    usernamePlace: '请输入邮箱',
                    passwordPlace: '请输入密码'
                },
                registerText: {
                    register: '注册',
                },
                titleMessage: 'Mnnu Online Judge',
                routerName: 'home',
                isLogin: false,
                login_data: {
                    email: '',
                    password: '',
                    email_error:'',
                    password_error:'',
                },
                loading:false
            }
        },
        methods: {
            login: function () {
                $('#Lusername').removeClass('is-invalid');
                $('#Lpassword').removeClass('is-invalid');
                if(!this.login_data.email){
                    $('#Lusername').addClass('is-invalid');
                    this.login_data.email_error=this.loginText.usernamePlace;
                }else if(!this.login_data.password){
                    $('#Lpassword').addClass('is-invalid');
                    this.login_data.password_error=this.loginText.passwordPlace;
                }else{
                    this.loadingBegin();
                    axios.post('/login', {
                        email: this.login_data.email,
                        password: this.login_data.password,
                    }).then(response => {
                        this.loadingEnd();
                        let data=response.data;
                        if(data.status){
                            axios.defaults.headers.common['Authorization']=data.data.token.token_type + ' ' + data.data.token.access_token;
                            let resp={
                                'type':2,
                                'token':data.data.token.token_type + ' ' + data.data.token.access_token,
                            };
                            ws.send(JSON.stringify(resp));
                            $('#login').modal("hide");
                            this.loginText.nickname=data.data.user.name;
                            this.isLogin=true;
                            document.cookie='M_OJ_token='+data.data.token.token_type + ' ' + data.data.token.access_token;
                            if (this.$route.path=='/register'){//在注册页面使用登陆框进行登陆成功后跳转到主页
                                this.$router.push("/");
                            }
                            this.globalLoginData.setData(true);//全局登陆标记设置 登陆
                            this.globalLoginData.setUserID(data.data.user.id);//全局用户名
                            this.globalLoginData.setUserData(data.data.user);
                        }else{
                            if(data.data.email){
                                $('#Lusername').addClass('is-invalid');
                                this.login_data.email_error=data.data.email;
                            }
                            if(data.data.password){
                                $('#Lpassword').addClass('is-invalid');
                                this.login_data.password_error=data.data.password;
                                this.login_data.password='';
                            }
                        }
                    }).catch(()=>{
                        this.loadingEnd();
                    });
                }
            },
            loadingBegin(){//显示登陆中
                $("#loginbtn").attr("disabled","disabled");
                this.loading=true;
            },
            loadingEnd(){//关闭显示
                this.loading=false;
                $('#loginbtn').removeAttr("disabled");
                $('#Lusername').removeClass('is-invalid');
                $('#Lpassword').removeClass('is-invalid');
            },
            showLoginModal(){//打开登陆框
                this.loadingEnd();
                $("#login").modal();
                $('#login').on('shown.bs.modal',function(e){
                    $('#Lusername').focus(); //通过ID找到对应输入框，让其获得焦点
                });
            },
            getUserName:function(){
                if(this.globalLoginData.islogin==true){
                    return;
                }
                let arr,reg=new RegExp("(^| )"+'M_OJ_token'+"=([^;]*)(;|$)");
                if(arr=document.cookie.match(reg)){
                    axios.defaults.headers.common['Authorization']=arr[2];
                    axios.post('/get_details').then(response => {
                        let data=response.data;
                        if(data.status){
                            this.loginText.nickname=data.data.user.name;
                            this.isLogin=true;
                            this.globalLoginData.setData(true);//全局登陆标记设置 登陆
                            this.globalLoginData.setUserID(data.data.user.id);
                            this.globalLoginData.setUserData(data.data.user);
                            let resp={
                                'type':2,
                                'token':arr[2]
                            };
                            ws.send(JSON.stringify(resp));
                        }
                    }).catch();
                }
            },
            logout:function () {
                axios.post('/logout').then(response => {
                    let data=response.data;
                    if(data.status){
                        this.isLogin=false;
                        this.globalLoginData.setData(false);//全局登陆标记 退出
                        this.$router.push("/");
                    }
                });
            },
            virify(){
                axios.post('/verify').then(response=>{
                    this.$toastr.s('系统已经向您的邮箱发送了激活邮件，请前往查看！');
                });
            },
            forgetPassword(){
                $("#login").modal('hide');
                setTimeout(()=>{
                    $("#forgetPassword").modal();
                },400);
            }
        },
        watch: {
            routerName: function (newVal, oldVal) {//routerName变化 改变导航栏激活
                var patt1 = new RegExp(newVal);
                $("#navbarSupportedContent>ul>li").each(function (index, element) {
                    var html = $(this).html();
                    if (patt1.test(html) == true) {
                        $(this).addClass("active");
                    } else {
                        $(this).removeClass("active");
                    }
                });
            },
            '$route.path': function (newVal, oldVal) {//观察路由变化设置routerName
                oldVal=='/register'&&this.getUserName();
                var t=newVal.split("/",2);
                var p = t[1];
                if (p == "problemDetail")
                    p = "problem";
                else if (p == "contestDetail")
                    p = "contest";
                else if (p=="examDetail")
                    p = "exam"
                this.routerName = p;
            }
        },
        created() {
            this.getUserName();
            var defaultpath = this.$route.path;//设置导航栏默认激活
            var t=defaultpath.split("/",2);
            var p = t[1];console.log(p);
            if (p == "problemDetail")
                p = "problem";
            else if (p == "contestDetail")
                p = "contest";
            else if (p=="examDetail")
                p = "exam";
            this.routerName = p;

            let keyListener = e =>{//设置键盘enter事件
                if(e.keyCode == 13&&$('body').hasClass('modal-open')==true){//有模态框打开时才相应
                    if (this.isLogin==false&&$("#login").css("display")!="none")
                        $("#loginbtn").click();
                    else
                        $("#submit").click();
                }
            };
            document.onkeydown=keyListener;
        },
    }
</script>

<style scoped>
    #login_form div label {
        padding-top: 8px;
    }
    .navbar{
        margin:0;
        background-color:#F5F5F5
    }
    .active{
        color: rgba(0, 0, 0, 0.9);
        background-color: rgba(0, 0, 0, 0.1);
    }
</style>
