<template>
    <div class="container">
        <div class="card">
            <span class="h1 card-header">{{statusTitle}}</span>
            <div class="form-inline">
                <div class="col mx-auto">
                    <span for="uId">&ensp;{{formText.userId}}&nbsp;</span>
                    <input id="uId" type="text" class="form-control" v-model="formData.uId" size="10"/>
                    <span for="resultSelect">&ensp;{{formText.result}}&nbsp;</span>
                    <select id="resultSelect" class="form-control" v-model="formData.resultSelect">
                        <option value=''>All</option>
                        <option v-for="(item,index) in formText.resultSelect" v-bind:value="index">{{item}}</option>
                    </select>
                    <span for="languageSelect">&ensp;{{formText.language}}&nbsp;</span>
                    <select id="languageSelect" class="form-control" v-model="formData.languageSelect">
                        <option value=''>All</option>
                        <option v-for="(item,index) in formText.languageSelect" v-bind:value="index">{{item}}</option>
                    </select>
                    <button class="btn btn-default" v-on:click="load">{{formText.submit}}</button>
                </div>

            </div>

            <div class="table-responsive card-body" style="overflow-y: hidden">
                <table class="table-bordered col">
                    <thead>
                    <tr>
                        <th v-for="value in statusHeadText">{{value}}</th>
                    </tr>
                    </thead>
                    <transition-group name="list" tag="tbody">
                        <tr v-for="item in status_list" v-bind:key="item['runid']" id="status_list" v-bind:class="item['result']==6?'table-danger':item['result']==4?'table-success':'table-info'+' list-item'" >
                            <td>{{item['runid']}}</td>
                            <td>{{item['user']}}</td>
                            <td>{{item['pid']}}</td>
                            <td v-bind:class="item['result']==6?'text-danger':item['result']==4?'text-success':'text-info'">{{formText.resultSelect[item['result']]}}</td>

                            <td v-if="item['user_id']==globalUID">
                                <router-link v-bind:to="'/viewCode/'+item['runid']" tag="a" target="_blank">{{formText.languageSelect[item['language']]}}</router-link>
                            </td>
                            <td v-else>{{formText.languageSelect[item['language']]}}</td>
                            <td>{{item['code_len']}}</td>
                            <td>{{item['time']}}</td>
                            <td>{{item['mem']}}</td>
                            <td>{{item['submit_time']}}</td>
                        </tr>
                    </transition-group>
                </table>
            </div>

            <paging ref="page" v-on:page-change="getPage" page-id="problemStatusPage"></paging>


        </div>


    </div>
</template>

<script>
    import Paging from "../other/paging";
    export default {
        name: "problemStatus",
        components: {Paging},
        data(){
            return {
                statusTitle:'Problem Status',
                formText: {
                    problemId: 'Problem ID:',
                    userId: 'User ID:',
                    result: 'Result:',
                    language: 'Language:',
                    submit:'Search',
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
                    ]
                },
                formData:{
                    pId:'',
                    uId:'',
                    resultSelect:'',
                    languageSelect:''
                },
                statusHeadText:{
                    id:'ID',
                    user:'User',
                    problemId:'Problem ID',
                    status:'Status',
                    language:'Language',
                    codeLen:'Code Len.',
                    time:'Time',
                    mem:'Mem.',
                    submitTime:'Submit Time'
                },
                status_list:[],
                p_id:this.$route.params.id,
                currentPage:-1,
                pageTotal:1,
                globalUID:''
            }
        },
        mounted() {
            this.load();
        },
        methods:{
            load(){
                this.$refs.page.locking();//加载前上锁
                axios.post('/problem/status',{
                    'u_id':this.formData.uId,
                    'language':this.formData.languageSelect,
                    'result':this.formData.resultSelect,
                    'p_id':this.p_id,
                    'page':this.currentPage
                }).then(response => {
                    this.globalUID=this.globalLoginData.userID;
                    let list=response.data.data;
                    this.pageTotal=response.data.last_page;
                    if(this.currentPage==-1){
                        this.$refs.page.initPage(this.pageTotal);//初始化 自动选择第1页
                    }
                    while(this.status_list.pop()){
                    }
                    list.forEach(item=>{
                        this.status_list.push(item);
                    });
                    this.$refs.page.unLock(); //加载后解锁
                });
            },
            getPage:function (page) {
                this.currentPage=page;
                this.load();
                this.$refs.page.goPage();
            },
        },
    }
</script>

<style scoped>

</style>