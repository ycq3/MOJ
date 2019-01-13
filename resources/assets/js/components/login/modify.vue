<template>
    <div class="container">
        <div class=" col-md-6 offset-md-3">

            <div class="modal-header col-sm-12">
                <h4 class="col text-center" id="modifyTitle">
                    {{modifyTitile}}
                </h4>
            </div>

            <div class="modal-body text-left">
                <div class="form-group row">
                    <label class="col-sm-3  form-control-label" for="username">{{modifyText.email}}</label>
                    <div class="col-sm-9">
                        <input id="username" class="form-control" v-bind:placeholder="inputPrompt.email" type="email"
                               v-model="bindingText.email" readonly="readonly" required/>
                        <div class="invalid-feedback">
                            {{errorMessage.usernameMsg}}
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-3  form-control-label" for="oldpassword">{{modifyText.oldpassword}}</label>
                    <div class="col-md-9">
                        <input id="oldpassword" class="form-control" v-bind:placeholder="inputPrompt.oldpassword"
                               type="password"
                               v-model="bindingText.oldpassword" required/>
                        <div class="invalid-feedback">
                            {{errorMessage.oldPasswordMsg}}
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 float-left" for="modifypassword">{{modifyText.modifypassword}}</label>
                    <div class="col-sm-9">
                        <input id="modifypassword" class="form-control" v-bind:placeholder="inputPrompt.modifypassword"
                               type="password"
                               v-model="bindingText.modifypassword"/>
                        <div class="invalid-feedback">
                            {{errorMessage.modifypassword}}
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-3 float-left" for="confirmPassword">{{modifyText.confirmPassword}}</label>
                    <div class="col-sm-9">
                        <input id="confirmPassword" class="form-control"
                               v-bind:placeholder="inputPrompt.confirmPassword" type="password"
                               v-model="bindingText.confirmPassword"/>
                        <div class="invalid-feedback">
                            {{errorMessage.confirmPasswordMsg}}
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-3 form-control-label" for="nickName">{{modifyText.nickName}}</label>
                    <div class="col-sm-9">
                        <input id="nickName" class="form-control" v-bind:placeholder="inputPrompt.nickName" type="text"
                               v-model="bindingText.nickName" required/>
                        <div class="invalid-feedback">
                            {{errorMessage.nicknameMsg}}
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-3 form-control-label">{{modifyText.sex.sex}}</label>
                    <div class="col-sm-9 text-center">
                        <label class="radio-inline">
                            <input type="radio" value="0" name="sex" v-model="bindingText.sex"/>{{modifyText.sex.secrecy}}
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="1" name="sex" v-model="bindingText.sex"/>{{modifyText.sex.male}}
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="2" name="sex" v-model="bindingText.sex"/>{{modifyText.sex.female}}
                        </label>
                    </div>
                </div>


                <div class="form-group row ">
                    <label class="col-sm-3 form-control-label" for="quote">{{modifyText.quote}}</label>
                    <div class="col-sm-9">
                    <textarea id="quote" class="form-control" rows="4" v-bind:placeholder="inputPrompt.quote"
                        v-model="bindingText.quote"></textarea>
                        <div class="invalid-feedback">
                            {{errorMessage.quoteMsg}}
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer mx-auto">
                <button class="btn btn-primary" v-on:click="update">{{modifyText.submit}}</button>
                <button class="btn btn-primary" v-on:click="modifyreset">{{modifyText.reset}}</button>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: "modify",
        data() {
            return {
                modifyTitile: '修改信息',
                errorMessage: {//错误信息
                    usernameMsg: '',
                    oldPasswordMsg: '',
                    modifypassword: '',
                    confirmPasswordMsg: '',
                    nicknameMsg: '',
                    quoteMsg: ''
                },
                modifyText: {
                    email: '邮箱',
                    oldpassword: '旧密码',
                    modifypassword: '修改密码',
                    confirmPassword: '确认密码',
                    nickName: '昵称',
                    sex: {
                        sex: '性别',
                        male: '男性',
                        female: '女性',
                        secrecy: '保密'
                    },
                    quote: '自我介绍',
                    submit: '提交修改',
                    reset: '重置'
                },
                bindingText: {
                    email: '',
                    oldpassword: '',
                    modifypassword: '',
                    confirmPassword: '',
                    nickName: '',
                    sex: 0,
                    quote: ''
                },
                inputPrompt: {
                    // email: '请输入用户名',
                    oldpassword: '请输入密码',
                    modifypassword: '请输入新密码',
                    confirmPassword: '请确认输入的新密码',
                    nickName: '请输入昵称',
                }
            }
        },
        methods: {
            modifyreset: function () {//输入重置
                Object.assign(this.$data, this.$options.data())
            },
            inputCheck: function () {//输入检查
                $("#oldpassword").removeClass('is-invalid');
                $("#modifypassword").removeClass('is-invalid');
                $("#confirmPassword").removeClass('is-invalid');
                $("#nickName").removeClass('is-invalid');
                $("#quote").removeClass('is-invalid');
                if (this.bindingText.oldpassword == "") {
                    this.errorMessage.oldPasswordMsg = "旧密码不能为空";
                    $("#oldpassword").addClass('is-invalid');
                } else if (this.bindingText.oldpassword.length > 200) {
                    this.errorMessage.oldPasswordMsg = "长度不超过200字符";
                    $("#oldpassword").addClass('is-invalid');
                } else if (this.bindingText.oldpassword.length < 6) {
                    this.errorMessage.oldPasswordMsg="密码长度不低于6位";
                    $("#oldpassword").addClass('is-invalid');
                } else if (this.bindingText.modifypassword != ""&&this.bindingText.modifypassword.length < 6) {
                    this.errorMessage.modifypassword = "密码长度不低于6位";
                    $("#modifypassword").addClass('is-invalid');
                } else if (this.bindingText.modifypassword.length > 200) {
                    this.errorMessage.modifypassword = "长度不超过200字符";
                    $("#modifypassword").addClass('is-invalid');
                } else if (this.bindingText.confirmPassword != ""&&this.bindingText.confirmPassword.length < 6) {
                    this.errorMessage.confirmPasswordMsg = "密码长度不低于6位";
                    $("#confirmPassword").addClass('is-invalid');
                } else if (this.bindingText.confirmPassword.length > 200) {
                    this.errorMessage.confirmPasswordMsg = "长度不超过200字符";
                    $("#confirmPassword").addClass('is-invalid');
                } else if (this.bindingText.confirmPassword != this.bindingText.modifypassword) {
                    this.errorMessage.confirmPasswordMsg = "密码错误";
                    $("#confirmPassword").addClass('is-invalid');
                } else if (this.bindingText.nickName == "") {
                    this.errorMessage.nicknameMsg = "昵称不能为空";
                    $("#nickName").addClass('is-invalid');
                } else if (this.bindingText.nickName.length > 200) {
                    this.errorMessage.nicknameMsg = "长度不超过200字符";
                    $("#nickName").addClass('is-invalid');
                } else if (this.bindingText.quote.length > 200) {
                    this.errorMessage.quoteMsg = "长度不超过200字符";
                    $("#quote").addClass('is-invalid');
                } else {
                    return true;
                }

                return false;
            },
            load:function () {
                axios.post('/get_details').then(response => {
                    let data=response.data;
                    if(data.status){
                        data=data.data;
                        this.bindingText.email=data.user.email;
                        this.bindingText.nickName=data.user.name;
                        this.bindingText.quote=data.user.quote;
                        this.bindingText.sex=data.user.sex;
                        //data.user.school
                    }
                });
            },
            update:function () {
                if(this.inputCheck()){
                    axios.post('passport/update_information',{
                        'email':this.bindingText.email,
                        'name':this.bindingText.nickName,
                        'o_password':this.bindingText.oldpassword,
                        'n_password':this.bindingText.modifypassword,
                        'c_password':this.bindingText.confirmPassword,
                        'quote':this.bindingText.quote,
                        'sex':this.bindingText.sex
                    }).then(response=>{
                        let data=response.data;
                        if(data.status){
                            //操作成功，后面执行以下对应的函数
                            this.$toastr.s(data.message);
                            this.$router.push("/");//跳转到主页
                            setTimeout("location.reload();",10);//刷新页面更新数据
                        }else{
                            //一般是密码错误，前端把旧密码那边标记为错误一下
                            this.$toastr.e(response.data.message);
                            this.errorMessage.oldPasswordMsg="密码错误";
                            $("#oldpassword").addClass('is-invalid');
                        }
                    });
                }
            }
        },
        mounted(){
            this.load();
        }
    }
</script>
<style scoped>
    .form-group label {
        padding-top: 8px;
    }
</style>