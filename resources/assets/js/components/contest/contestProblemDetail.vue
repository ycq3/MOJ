<template>
    <div class="container card-deck-wrapper text-left">
        <div class="card-deck">
            <div id="pro" class="card overflow">
                <div id="head" class="text-center card-header">
                    <div class="card-title">
                        <h1>{{problemData.title}}</h1>
                    </div>
                    <div class="card-text">
                        <div>
                            <small>Time Limit: {{problemData.time}}/{{problemData.other_time}} MS (C/Others) &emsp;</small>
                            <small> Memory Limit: {{problemData.memory}}/{{problemData.other_memory}} K (C/Others)</small>
                        </div>
                    </div>
                </div>
                <div>
                    <div id="pDescription" class="col">
                        <div class="card-title">
                            <span class="h3">Problem Description:</span>
                        </div>
                        <div v-html="problemData.describe" class="card-text bg-style">

                        </div>
                    </div>
                    <div id="input" class="col">
                        <div class="card-title">
                            <h3>Input:</h3>
                        </div>
                        <div v-html="problemData.input" class="card-text bg-style">
                        </div>
                    </div>
                    <div id="output" class="col">
                        <div class="card-title">
                            <h3>Output:</h3>
                        </div>
                        <div v-html="problemData.output" class="card-text bg-style">
                        </div>
                    </div>
                    <div id="sInput" class="col">
                        <div class="card-title">
                            <h3>Sample Input:</h3>
                        </div>
                        <div class="card-text bg-style">
                            <pre v-html="problemData.sampleinput"></pre>
                        </div>
                    </div>
                    <div id="sOutput" class="col">
                        <div class="card-title">
                            <h3>Sample Output:</h3>
                        </div>
                        <div class="card-text bg-style">
                            <pre v-html="problemData.sampleoutput"></pre>
                        </div>
                    </div>
                    <div id="hint" class="col" v-show="problemData.hint!=''" key="hint">
                        <div class="card-title">
                            <h3>Hint:</h3>
                        </div>
                        <div class="card-text bg-style">
                            <pre v-html="problemData.hint"></pre>
                        </div>
                    </div>
                    <div id="source" class="col" v-show="problemData.source!=''" key="source">
                        <div class="card-title">
                            <h3>Source:</h3>
                        </div>
                        <div class="card-text bg-style">
                            <pre v-html="problemData.source"></pre>
                        </div>
                    </div>
                </div>

            </div>
            <!--编辑器-->
            <div class="card col-sm-8 col-12" v-if="editorShow">
                <div id="edit" class="col-12 float-right">
                    <div style="background-color: rgba(0, 0, 0, 0.03)" class="form-inline">
                        <select class="form-control" v-model="selectData.selected">
                            <option v-for="(item,index) in selectData.language" v-bind:value="index">{{item}}</option>
                        </select>
                        <div class="col">
                            <button type="button" class="close float-right form-control" aria-label="Close" v-on:click="show()">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                        </div>
                    </div>
                    <div class="row" style="font-size: large">
                        <codemirror
                                id="editor"
                                class="text-left col-sm-12"
                                v-model="editorCode"
                                :options="cmOptions">
                        </codemirror>
                    </div>
                    <div class="row" style="padding: 5px">
                        <div class="col text-center">
                            <button class="btn btn-primary" style="width: 69px" v-on:click="clear">{{btnText.clearBtn}}</button>
                        </div>
                        <div class="col text-center">
                            <button class="btn btn-success" style="width: 69px" v-on:click="submit">{{btnText.submitBtn}}</button>
                        </div>


                    </div>
                </div>
            </div>
            <!--编辑器end-->
        </div>

        <div class="card-footer">
            <div class="col text-center">
                <button class="btn btn-primary" style="width: 69px" v-on:click="show">{{editorBtn}}</button>&ensp;
                <button id="historyBtn" style="width: 69px" class="btn btn-success" v-on:click="viewHistory">{{showHistory}}</button>
            </div>
        </div>

        <!-- 提交记录 -->
        <div class="modal fade" id="subHistory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{subHistoryTitle}}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">×
                        </button>
                    </div>
                    <div class="modal-body table-responsive">
                        <table class="table text-center">
                            <thead>
                            <tr>
                                <th v-for="value in subTableText">{{value}}</th>
                            </tr>
                            </thead>
                            <transition-group name="list" tag="tbody">
                                <tr v-for="item in status_list" v-bind:key="item['runid']" id="status_list" v-bind:class="item['result']==6?'table-danger':item['result']==4?'table-success':'table-info'+' list-item'" >
                                    <td v-bind:class="item['result']==6?'text-danger':item['result']==4?'text-success':'text-info'">{{resultSelect[item['result']]}}</td>
                                    <td>
                                        <router-link v-bind:to="'/viewCode/'+item['runid']" tag="a" target="_blank">{{languageSelect[item['language']]}}</router-link>
                                    </td>
                                    <td>{{item['submit_time']}}</td>
                                    <td>{{item['time']}}</td>
                                    <td>{{item['mem']}}</td>
                                </tr>
                            </transition-group>
                        </table>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div>
</template>

<script>
    import { codemirror } from 'vue-codemirror'//必需
    import 'codemirror/lib/codemirror.css'//必需
    import 'codemirror/theme/material.css'//主题样式
    import 'codemirror/mode/clike/clike.js'//导入类c,关键字高亮
    export default {
        name: "contestProblemDetail",
        components: {
            codemirror
        },
        data() {
            return {
                cmOptions: {//codemirror配置项
                    mode: 'text/x-c++src',//C++模式 关键字见： https://codemirror.net/mode/clike/index.html#
                    theme: 'material',//主题
                    lineNumbers: true,//行号
                    indentUnit:4,//缩进4个空格
                    showCursorWhenSelecting:true,//选择时是否显示光标
                    autofocus:true,//获得焦点
                },
                selectData:{//语言选择
                    language:{},
                    selected:'',
                },
                editorShow: false,
                showHistory: 'History',
                editorCode: '',
                editorBtn: 'Editor',
                subHistoryTitle: 'Submit History',
                subTableText: {
                    status: 'Status',
                    language: 'Language',
                    submitTime: 'Submit Time',
                    time: 'Time',
                    mem: 'Mem.',
                },
                problemData:{//题目信息
                    title: '',
                    describe: '',
                    input: '',
                    output: '',
                    sampleinput: '',
                    sampleoutput: '',
                    hint: '',
                    source:'',
                    time: '',
                    memory: '',
                    other_time: '',
                    other_memory: '',
                },
                btnText:{
                    submitBtn:'Submit',
                    clearBtn:'Clear'
                },
                c_id:this.$route.params.id,
                p_id:this.$route.params.p_id,
                status_list:[],
                resultSelect:[
                    'Waiting',
                    'Rejudge',
                    'Compile',
                    'judging',
                    'Accepted',
                    'Presentation Error',
                    'Wrong Answer',
                    'Time Limit Exceeded',
                    'Memory Limit Exceeded',
                    'Output Limit Exceeded',
                    'Runtime Error',
                    'Compile Error',
                    'Compiled',
                    'Running'
                ],
                languageSelect:[
                    'C',
                    'C++',
                    'Pascal',
                    'java',
                    'Ruby',
                    'Bash',
                    'Python',
                    'PHP',
                    'Perl',
                    'C#',
                    'Object-C',
                    'FreeBasic',
                    'Schema',
                    'Clang',
                    'Clang++',
                    'Lua',
                    'JavaScript',
                    'Go',
                    'Other'
                ],
            }
        },
        methods: {
            show() {
                this.editorShow = !this.editorShow;
                if (this.editorShow == true) {
                    $("#pro").addClass("float-left").addClass("col-lg-4");
                    this.editorBtn = 'Close';
                } else {
                    $("#pro").removeClass("float-left").removeClass("col-lg-4");
                    this.editorBtn = 'Editor';
                }
            },
            clear() {
                this.editorCode = '';
            },
            //加载数据
            load() {
                //获取问题详情
                axios.post('/contest/show/'+this.c_id+'/problem/' + this.p_id).then(response => {
                    let data = response.data;
                    this.problemData=data;
                    this.selectData.language=data['language'];//语言下拉框
                    this.selectData.selected=Object.keys(this.selectData.language)[0];//获得第一种语言键值
                });
            },
            submit(){//提交代码
                if (this.globalLoginData.islogin==false){//提交前登陆检查
                    $("#login").modal("show");
                }else if (this.editorCode!='') {
                    axios.post('/contest/submit/'+this.c_id+'/problem/'+this.p_id,{
                        'language':this.selectData.selected,
                        'code':this.editorCode
                    }).then(response=>{
                        this.$toastr.s("代码提交成功！提交编号"+response.data);
                        $("#historyBtn").click();
                    }).catch(function(error){
                        let code=error.response.status;
                        if(code == 401){
                            this.$toastr.e('请登录');
                        }else if(code == 404){
                            this.$toastr.e('请求的资源不存在');
                        }
                    });
                }
            },
            viewHistory(){//查看历史记录前检查登陆
                if (this.globalLoginData.islogin==false){
                    $("#login").modal("show");
                } else {
                    $("#subHistory").modal();
                    axios.post('/contest/status/'+ this.c_id+'/problem/'+this.p_id).then(response=>{
                        let list=response.data.data;
                        this.status_list=list;
                    });
                }
            }
        },
        created() {
            this.load();
        },
    }
</script>

<style scoped>

    .modal-dialog {
        margin: 80px auto;
    }

    #pro{
        height: 610px;
    }
    .overflow {
        overflow: auto;
    }
    .bg-style{
        border-color: #c9e2f3;
        border-style: dashed;
        border-width: 1px;
        background-color: #F0F8FF;
    }
</style>
<style>
    .CodeMirror {
        height: 524px;
    }
</style>