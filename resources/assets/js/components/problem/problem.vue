<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="text-center">{{title}}</h1>
            </div>
            <div class="row card-body">
                <div id="problems" class="col-sm-9">
                    <div class="table-responsive" style="overflow-y: hidden;">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                            <tr>
                                <th v-for="value in problemHeadText">{{value}}</th>
                            </tr>
                            </thead>
                            <!--<tbody>-->
                            <transition-group name="list" tag="tbody">
                                <tr v-for="item in problem_list" v-bind:key="item['id']" id="problem_list"
                                    class="list-item">

                                    <td>{{ item['ac_flag']==true?"√":""}}</td>
                                    <td>{{item['id']}}</td>
                                    <td>
                                        <div class="problem" style="padding-top: 0px">
                                            <router-link v-bind:to="/problemDetail/+item['id']">{{item['title']}}
                                            </router-link>
                                            <ul class="details" style="margin-bottom: 0px">
                                                <li>
                                                    {{problemText.pass_rate}}{{(item['submited']?item['accepted']/item['submited']*100:0).toFixed(2)}}%
                                                </li>
                                                <li>{{problemText.pass_number}}{{item['accepted']}}</li>
                                                <li>{{problemText.submit_number}}{{item['submited']}}</li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </transition-group>
                            <!--</tbody>-->
                        </table>
                    </div>
                    <!--分页组件 传入pageId作为唯一标识-->
                    <paging ref="page" v-on:page-change="getPage" page-id="problemPage"></paging>
                </div>
                <div id="form" class="col-sm-3 card-body">
                    <!--搜索-->
                    <div>
                        <h3 id="searchTitle" class="title">{{problemText.search}}</h3>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="请输入问题题目" v-model="searchText" />
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2"
                                        v-on:click="search">Go
                                </button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!--分类-->
                    <div id="select">
                        <h3 class="title">{{problemText.problem_classification}}</h3>
                        <transition-group name="list" tag="ul" class="list-unstyled">
                            <li v-for="item in problemTypeName" class="input-group" v-bind:key="item['id']">
                                <label v-bind:for="item['id']" class="form-control">
                                    <input type="checkbox" v-bind:id="item['id']" name="problemType"
                                           v-bind:value="item['id']" v-model="checkboxData" style="opacity: 0"/>
                                    <span class="c-indicator">{{item['tag_name']}}</span>
                                </label>
                            </li>
                        </transition-group>
                        <button v-on:click="tag_select" class="btn btn-primary">确定</button>
                    </div>
                </div><!--form-->
            </div><!-- row-->
        </div>

    </div>
</template>

<script>
    import paging from "../other/paging";
    export default {
        name: "problem",
        components: {paging},
        data() {
            return {
                problemText: {
                    search: '搜索',
                    problem_classification: '问题分类',
                    pass_rate: '通过率:',
                    pass_number: '通过人数:',
                    submit_number: '提交人数:'
                },
                title: 'Problem List',
                searchText: '',
                checkboxData: [],
                problemTypeName: [],
                problemHeadText: {
                    solved: 'solved',
                    id: 'ID',
                    subject: 'subject'
                },
                problem_list: [],
                currentPage:-1,
                pageTotal:0,
            }
        },
        mounted() {
            this.load();
        },
        methods: {
            //加载数据
            load() {
                this.load_problem();
                //获取分类标签
                axios.post('/problem/tags').then(response => {
                    if(response.data.status!=null&&response.data.status==false){
                        this.$toastr.e(response.data.message);
                    }
                    let list = response.data;
                    list.forEach(function (item) {
                        this.splice(this.length, 0, item);
                    }, this.problemTypeName);
                });
            },
            load_problem() {
                this.$refs.page.locking();//加载前上锁
                while(this.problem_list.pop()){}
                //获取问题列表
                axios.post('/problem', {
                        'page': this.currentPage,//当前页号
                        'tag': this.checkboxData,//选中的标签
                        'keyword': this.searchText//查询关键字
                    }
                ).then(response => {
                    let list = response.data['data'];
                    this.pageTotal=response.data.last_page;
                    if(this.currentPage==-1){
                        this.$refs.page.initPage(this.pageTotal);//初始化 自动选择第1页
                    }
                    list.forEach(item=> {
                        this.problem_list.push(item);
                    });
                    this.$refs.page.unLock(); //加载后解锁
                });
            },
            search() {
                //清除标签选中
                while (this.checkboxData.pop()) {}
                this.currentPage=-1;
                this.load_problem();//根据输入加载问题列表
                //this.$refs.page.initPage(this.pageTotal);//初始化 自动选择第1页
            },
            tag_select(){
                this.currentPage=-1;
                this.load_problem();
                //this.$refs.page.initPage(this.pageTotal);//初始化 自动选择第1页
            },
            getPage:function (page) {//分页组件的当前页数改变后会调用这个函数
                this.currentPage=page;
                this.load_problem();
                this.$refs.page.goPage();
            }
        }
    }
</script>

<style scoped>
    .details li {
        display: inline-block;
        font-size: small;
        color: #999;
    }

    table th, table td {
        text-align: center;
        vertical-align: middle !important;
    }

    table td div {
        padding-top: 18px;
    }

    table {
        font-size: large;
    }

    input:checked + span {
        color: #337ab7;
        font-weight: bolder;
    }
</style>
