<template>
    <div class="container">
        <div class="h1">
            {{title}}
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-10 offset-sm-1">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <span class="col">{{codeDetailText.user}}{{codeDetailData.user}}</span>
                            <span class="col">{{codeDetailText.problemId}}{{codeDetailData.problem_id}}</span>
                            <span class="col">{{codeDetailText.runId}}{{codeDetailData.runid}}</span>
                        </div>
                        <div>
                            <span class="col">{{codeDetailText.language}}{{codeDetailData.language}}</span>
                            <span class="col">{{codeDetailText.time}}{{codeDetailData.time}}</span>
                            <span class="col">{{codeDetailText.memory}}{{codeDetailData.mem}}</span>
                        </div>
                        <div>
                            <span>{{codeDetailText.result}}
                                <span v-bind:class="codeDetailData.result==6?'text-danger':codeDetailData.result?'text-success':'text-info'">
                                    {{resultType[codeDetailData.result]}}
                                </span>
                            </span>
                        </div>
                        <div v-show="codeDetailData.info!=null">
                            <span>{{codeDetailText.info}}{{codeDetailData.info}}</span>
                        </div>
                        <div>
                            <button class="btn btn-primary" v-on:click="CopyToClipboard">{{copyText}}</button>
                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#passDetail" aria-expanded="false" aria-controls="passDetail">
                                {{viewPassDetailText}}
                            </button>
                        </div>

                        <div class="collapse" id="passDetail">
                            <div class="card card-block">
                                <br>
                                <div class="text-success">{{codeDetailText.pass}}
                                    <span v-for="item in codeDetailData.pass_case" >&ensp;{{item}}</span>
                                </div>
                                <hr>
                                <div class="text-danger">{{codeDetailText.error}}
                                    <span v-for="item in codeDetailData.fail_case">&ensp;{{item}}</span>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-left">
                        <code><pre style="font-size: large">
                            <codemirror
                                id="editor"
                                class="text-left col-sm-12"
                                v-model="codeDetailData.code"
                                :options="cmOptions">
                        </codemirror>
                        </pre></code>
                    </div>
                </div>

            </div>
        </div>

        <hr>
        <br>
    </div>
</template>

<script>
    import {codemirror} from 'vue-codemirror'//必需
    import 'codemirror/lib/codemirror.css'//必需
    import 'codemirror/theme/material.css'//主题样式
    import 'codemirror/mode/clike/clike.js'//导入类c,关键字高亮
    export default {
        name: "viewCode",
        components: {
            codemirror
        },
        data(){
            return {
                cmOptions: {//codemirror配置项
                    mode: 'text/x-c++src',//C++模式 关键字见： https://codemirror.net/mode/clike/index.html#
                    theme: 'material',//主题
                    lineNumbers: true,//行号
                    indentUnit: 4,//缩进4个空格
                    showCursorWhenSelecting: true,//选择时是否显示光标
                    autofocus: true,//获得焦点
                },
                title:'viewCode',
                s_id:this.$route.params.id,
                copyText:'Copy to Clipboard',
                viewPassDetailText:'View Pass Detail',
                codeDetailText:{
                    user:'User: ',
                    problemId:'Problem ID: ',
                    runId:'Run ID: ',
                    language: 'Language: ',
                    time:'Time: ',
                    memory:'Memory: ',
                    result:'Result: ',
                    info:'Info: ',
                    pass:'Pass:',
                    error:'Error:',
                },
                codeDetailData:{
                    user:'',
                    runid:'',
                    problem_id:'',
                    language: '',
                    time:'',
                    mem:'',
                    result:'',
                    code:'',
                    info:'',
                    pass_case:[],
                    fail_case:[],
                },
                resultType:[
                    'Waiting',
                    'Rejudge',
                    'Compile',
                    'Judging',
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
            }
        },
        methods: {
            load(){
                axios.post('/problem/code_show/'+ this.s_id).then(response => {
                    let data=response.data;
                    this.codeDetailData=data;
                    // this.codeDetailData.result=4;
                });
            },
            CopyToClipboard () {//复制 参考 http://help.dottoro.com/ljctuhrg.php 创建对象模拟选中复制操作
                let textToClipboard = this.codeDetailData.code;

                let success = true;
                if (window.clipboardData) { // Internet Explorer
                    window.clipboardData.setData ("Text", textToClipboard);
                }
                else {
                    // create a temporary element for the execCommand method
                    let forExecElement = this.CreateElementForExecCommand (textToClipboard);

                    /* Select the contents of the element
                        (the execCommand for 'copy' method works on the selection) */
                    this.SelectContent (forExecElement);

                    let supported = true;

                    // UniversalXPConnect privilege is required for clipboard access in Firefox
                    try {
                        if (window.netscape && netscape.security) {
                            netscape.security.PrivilegeManager.enablePrivilege ("UniversalXPConnect");
                        }

                        // Copy the selected content to the clipboard
                        // Works in Firefox and in Safari before version 5
                        success = document.execCommand ("copy", false, null);
                    }
                    catch (e) {
                        success = false;
                    }

                    // remove the temporary element
                    document.body.removeChild (forExecElement);
                }

                if (success) {
                    this.$toastr.s("The text is on the clipboard, try to paste it!");
                }
                else {
                    this.$toastr.e("Your browser doesn't allow clipboard access!");
                }
            },
            CreateElementForExecCommand (textToClipboard) {
                let forExecElement = document.createElement ("div");

                let p=document.createElement("pre");//保存代码格式
                p.textContent=textToClipboard;

                // place outside the visible area
                forExecElement.style.position = "absolute";
                forExecElement.style.left = "-10000px";
                forExecElement.style.top = "-10000px";
                // write the necessary text into the element and append to the document

                forExecElement.appendChild(p);

                document.body.appendChild (forExecElement);
                // the contentEditable mode is necessary for the  execCommand method in Firefox
                forExecElement.contentEditable = true;

                return forExecElement;
            },
            SelectContent (element) {
                // first create a range
                let rangeToSelect = document.createRange ();
                rangeToSelect.selectNodeContents (element);

                // select the contents
                let selection = window.getSelection ();
                selection.removeAllRanges ();
                selection.addRange (rangeToSelect);
            }
        },
        created() {
            this.load();
        },
    }
</script>

<style scoped>

</style>