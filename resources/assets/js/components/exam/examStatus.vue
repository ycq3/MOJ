<template>
    <div class="container">
        <div class="card">
            <span class="h1 card-header">{{examStatusTitle}}</span>

            <div class="card-body">
                <div class="form-inline">
                    <div class="col mx-auto">
                        <span for="pId">{{examFormText.problemId}}&nbsp;</span>
                        <input id="pId" type="text" class="form-control" v-model="examFormData.pId" size="5"/>
                        <span for="uId">&ensp;{{examFormText.userId}}&nbsp;</span>
                        <input id="uId" type="text" class="form-control" v-model="examFormData.uId" size="10"/>
                        <span for="resultSelect">&ensp;{{examFormText.result}}&nbsp;</span>
                        <select id="resultSelect" class="form-control" v-model="examFormData.resultSelect">
                            <option value=''>All</option>
                            <option v-for="(item,index) in examFormText.resultSelect" v-bind:value="index">{{item}}
                            </option>
                        </select>
                        <span for="languageSelect">&ensp;{{examFormText.language}}&nbsp;</span>
                        <select id="languageSelect" class="form-control" v-model="examFormData.languageSelect">
                            <option value=''>All</option>
                            <option v-for="(item,index) in examFormText.languageSelect" v-bind:value="index">{{item}}
                            </option>
                        </select>
                        <button class="btn btn-default">{{examFormText.submit}}</button>
                    </div>

                </div>

                <div class="table-responsive" style="overflow-y: hidden;">
                    <table class="table-bordered col">
                        <thead>
                        <tr>
                            <th v-for="value in examStatusHeadText">{{value}}</th>
                        </tr>
                        </thead>
                        <transition-group name="list" tag="tbody">
                            <tr v-for="item in status_list" v-bind:key="item['runid']" id="status_list"
                                v-bind:class="item['result']==6?'table-danger':item['result']==4?'table-success':'table-info'+' list-item'">
                                <td>{{item['runid']}}</td>
                                <td>{{item['user']}}</td>
                                <td>{{item['pid']}}</td>
                                <td v-bind:class="item['result']==6?'text-danger':item['result']==4?'text-success':'text-info'">
                                    {{examFormText.resultSelect[item['result']]}}
                                </td>
                                <td>50</td>
                                <td v-if="item['user_id']==globalUID">
                                    <router-link v-bind:to="'/viewCode/'+item['runid']" tag="a" target="_blank">
                                        {{examFormText.languageSelect[item['language']]}}
                                    </router-link>
                                </td>
                                <td v-else>{{examFormText.languageSelect[item['language']]}}</td>
                                <td>{{item['code_len']}}</td>
                                <td>{{item['time']}}</td>
                                <td>{{item['mem']}}</td>
                                <td>{{item['submit_time']}}</td>
                            </tr>
                        </transition-group>
                    </table>
                </div>

                <paging ref="page" v-on:page-change="getPage" page-id="examStatusPage"></paging>
            </div>
        </div>


    </div>
</template>

<script>
    import Paging from "../other/paging";

    export default {
        name: "examStatus",
        components: {Paging},
        data() {
            return {
                examStatusTitle: 'Judge Status',
                examFormText: {
                    problemId: 'Problem ID:',
                    userId: 'User ID:',
                    result: 'Result:',
                    language: 'Language:',
                    submit: 'Search',
                    resultSelect:[
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
                examFormData: {
                    pId: '',
                    uId: '',
                    resultSelect: '',
                    languageSelect: ''
                },
                examStatusHeadText: {
                    id: 'ID',
                    user: 'User',
                    pId: 'Pro.ID',
                    status: 'Status',
                    score:'Score',
                    language: 'Language',
                    codeLen: 'Code Len.',
                    time: 'Time',
                    mem: 'Mem.',
                    submitTime: 'Submit Time'
                },
                e_id:this.$route.params.id,
                currentPage: -1,
                pageTotal: 1,
                status_list: [],
                globalUID:''
            }
        },
        methods: {
            load() {
                this.$refs.page.locking();//加载前上锁
                axios.post('/exam/status/' + this.e_id, {
                    'u_id': this.examFormData.uId,
                    'language': this.examFormData.languageSelect,
                    'result': this.examFormData.resultSelect,
                    'p_id': this.examFormData.pId,
                    'page': this.currentPage
                }).then(response => {
                    this.globalUID=this.globalLoginData.userID;
                    let list = response.data.data;
                    this.pageTotal = response.data.last_page;
                    if (this.currentPage == -1) {
                        this.$refs.page.initPage(this.pageTotal);//初始化 自动选择第1页
                    }
                    while (this.status_list.pop()) {
                    }
                    list.forEach(item => {
                        this.status_list.push(item);
                    });
                    this.$refs.page.unLock(); //加载后解锁
                });
            },
            getPage: function (page) {
                this.currentPage = page;
                this.load();
                this.$refs.page.goPage();
            },
        },
        mounted() {
            this.load();
        },
    }
</script>

<style scoped>

</style>
