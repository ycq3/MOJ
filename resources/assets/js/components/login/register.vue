<template>
    <div class="container">
        <div class=" col-md-6 offset-md-3">

            <div class="modal-header col-sm-12">
                <h4 class="col text-center" id="registerLabel">
                    {{registerText.register}}
                </h4>
            </div>

            <div class="modal-body text-left">
                <div class="form-group row">
                    <label class="col-sm-3  form-control-label" for="username">{{registerText.userID}}</label>
                    <div class="col-sm-9">
                        <input id="username" class="form-control" placeholder="请输入邮箱" type="email"
                               v-model="registerData.username" required/>
                        <div class="invalid-feedback">
                            {{errorMessage.usernameMsg}}
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-3  form-control-label" for="password">{{registerText.password}}</label>
                    <div class="col-md-9">
                        <input id="password" class="form-control" placeholder="请输入密码" type="password"
                               v-model="registerData.password" required/>
                        <div class="invalid-feedback">
                            {{errorMessage.passwordMsg}}
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-3 float-left" for="confirmPassword">{{registerText.confirmPassword}}</label>
                    <div class="col-sm-9">
                        <input id="confirmPassword" class="form-control" placeholder="请确认输入的密码" type="password"
                               v-model="registerData.confirmPassword" required/>
                        <div class="invalid-feedback">
                            {{errorMessage.confirmPasswordMsg}}
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-3 form-control-label" for="nickName">{{registerText.nickName}}</label>
                    <div class="col-sm-9">
                        <input id="nickName" class="form-control" placeholder="输入你的昵称" type="text"
                               v-model="registerData.nickname" required/>
                        <div class="invalid-feedback">
                            {{errorMessage.nicknameMsg}}
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-3 form-control-label">{{registerText.sex.sex}}</label>
                    <div class="col-sm-9 text-center">
                        <label class="radio-inline">
                            <input type="radio" value="0" name="sex" v-model="registerData.sex"/>{{registerText.sex.secrecy}}
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="1" name="sex" v-model="registerData.sex"/>{{registerText.sex.male}}
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="2" name="sex" v-model="registerData.sex"/>{{registerText.sex.female}}
                        </label>
                    </div>
                </div>


                <div class="form-group row ">
                    <label class="col-sm-3 form-control-label" for="quote">{{registerText.quote}}</label>
                    <div class="col-sm-9">
                    <textarea id="quote" class="form-control" rows="4" placeholder="长度不超过200字符"
                        v-model="registerData.quote"></textarea>
                        <div class="invalid-feedback">
                            {{errorMessage.quoteMsg}}
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" v-on:click="regist">{{registerText.register}}</button>
                <button class="btn btn-primary" v-on:click="initInput">{{registerText.reset}}</button>
                <a href="JavaScript:;" data-dismiss="modal" data-toggle="modal" data-target="#login">{{registerText.login}}</a>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: "register",
        data() {
            return {
                registerText: {//注册文本
                    register: '注册',
                    userID: '邮箱',
                    password: '密码',
                    confirmPassword: '确认密码',
                    nickName: '昵称',
                    sex: {
                        sex: '性别',
                        male: '男',
                        female: '女',
                        secrecy: '保密'
                    },
                    quote: '自我介绍',
                    reset: '重置',
                    login: '我已注册，立即登录',
                },
                registerData: {//输入框数据
                    username: '',
                    password: '',
                    confirmPassword: '',
                    nickname: '',
                    sex: '0',
                    quote: ''
                },
                errorMessage: {//错误信息
                    usernameMsg: '',
                    passwordMsg: '',
                    confirmPasswordMsg: '',
                    nicknameMsg: '',
                    quoteMsg: ''
                }
            }
        },
        methods: {
            initInput: function () {//输入数据初始化
                this.registerData = {
                    username: '',
                    password: '',
                    confirmPassword: '',
                    nickname: '',
                    sex: '0',
                    quote: '',
                }
            },
            inputCheck: function () {//输入检查
                $("#username").removeClass('is-invalid');
                $("#password").removeClass('is-invalid');
                $("#confirmPassword").removeClass('is-invalid');
                $("#nickName").removeClass('is-invalid');
                $("#quote").removeClass('is-invalid');
                var re = /^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/;
                if (this.registerData.username == "") {
                    this.errorMessage.usernameMsg = "用户名不能为空";
                    $("#username").addClass('is-invalid');
                } else if (this.registerData.username.length > 200) {
                    this.errorMessage.usernameMsg = "长度不超过200字符";
                    $("#username").addClass('is-invalid');
                } else if (!re.test(this.registerData.username)) {
                    this.errorMessage.usernameMsg = "邮箱格式错误";
                    $("#username").addClass('is-invalid');
                } else if (this.registerData.password == "") {
                    this.errorMessage.passwordMsg = "密码不能为空";
                    $("#password").addClass('is-invalid');
                } else if (this.registerData.password.length < 6) {
                    this.errorMessage.passwordMsg = "密码长度不低于6位";
                    $("#password").addClass('is-invalid');
                }else if (this.registerData.password.length > 200) {
                    this.errorMessage.passwordMsg = "长度不超过200字符";
                    $("#password").addClass('is-invalid');
                } else if (this.registerData.confirmPassword == "") {
                    this.errorMessage.confirmPasswordMsg = "密码不能为空";
                    $("#confirmPassword").addClass('is-invalid');
                } else if (this.registerData.confirmPassword.length < 6) {
                    this.errorMessage.confirmPasswordMsg = "密码长度不低于6位";
                    $("#confirmPassword").addClass('is-invalid');
                }else if (this.registerData.confirmPassword.length > 200) {
                    this.errorMessage.confirmPasswordMsg = "长度不超过200字符";
                    $("#confirmPassword").addClass('is-invalid');
                } else if (this.registerData.confirmPassword != this.registerData.password) {
                    this.errorMessage.confirmPasswordMsg = "密码错误";
                    $("#confirmPassword").addClass('is-invalid');
                } else if (this.registerData.nickname == "") {
                    this.errorMessage.nicknameMsg = "昵称不能为空";
                    $("#nickName").addClass('is-invalid');
                } else if (this.registerData.nickname.length > 200) {
                    this.errorMessage.nicknameMsg = "长度不超过200字符";
                    $("#nickName").addClass('is-invalid');
                } else if (this.registerData.quote.length > 200) {
                    this.errorMessage.quoteMsg = "长度不超过200字符";
                    $("#quote").addClass('is-invalid');
                } else {
                    return true;
                }

                return false;
            },
            regist: function () {
                if (this.inputCheck()) {
                    axios.post('/register',{
                        'email':this.registerData.username,
                        'name':this.registerData.nickname,
                        'password':this.registerData.password,
                        'c_password':this.registerData.confirmPassword,
                        'quote':this.registerData.quote,
                        'sex':this.registerData.sex
                    }).then(response=> {
                        let data=response.data;
                        if(data.status){
                            document.cookie='M_OJ_token='+data.data.token.token_type + ' ' + data.data.token.access_token;
                            if (this.$route.path=='/register'){//在注册页面进行登陆成功后跳转到主页
                                this.$router.push("/");
                            }
                            $("#resignHint").modal();
                        }else{
                            if(data.data['username']!=null){
                                this.errorMessage.usernameMsg = data.data['username'];
                                $("#username").addClass('is-invalid');
                            }
                            if(data.data['nickname']!=null){
                                this.errorMessage.nicknameMsg = data.data['nickname'];
                                $("#nickName").addClass('is-invalid');
                            }
                            if(data.data['quote']!=null){
                                this.errorMessage.quoteMsg = data.data['quote'];
                                $("#quote").addClass('is-invalid');
                            }
                        }
                    }).catch(e => {
                        this.$toastr.e("注册失败！");
                    });
                }
            }
        }
    }
</script>

<style scoped>
    .form-group label {
        padding-top: 8px;
    }
</style>
