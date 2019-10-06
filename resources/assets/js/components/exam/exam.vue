<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <h1 class="col text-center">{{examTitle}}</h1>
                </div>
                <div class="row form-inline">
                    <div class="col text-center">
                        <strong>{{searchTitle}}</strong>
                        <input type="text" class="form-control" v-model="searchText" placeholder="请输入考试题目"/>
                        <button class="btn btn-default" v-on:click="search">{{examSubmitText}}</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive card-body" style="overflow-y: hidden">
                <table class="table">
                    <thead>
                    <tr>
                        <th v-for="examHead in examHeadText">{{examHead}}</th>
                    </tr>
                    </thead>
                    <transition-group name="list" tag="tbody">
                        <tr v-for="item in exam_list" v-bind:key="item['id']" id="exam_list" class="list-item">
                            <td>{{item['id']}}</td>
                            <td>
                                <a href="javaScript:;" v-on:click="enterDetail(item['id'],item['type'])">{{item['title']}}</a>
                                <!--<router-link v-bind:to="/examDetail/+item['id']">{{item['title']}}</router-link>-->
                            </td>
                            <td>{{item['start_time']}}</td>
                            <td>{{item['end_time']}}</td>
                            <td>{{type[item['type']]}}</td>
                            <td>{{item['status']}}</td>
                        </tr>
                    </transition-group>
                </table>
            </div>
            <paging ref="page" class="card-footer" v-on:page-change="getPage" page-id="examPage"></paging>
        </div>


        <!--密码验证框-->
        <div class="modal fade" id="inputModal" role="dialog" aria-labelledby="inputModal"
             aria-hidden="true">
            <div class="modal-dialog modal-sm" style="top:25%;">
                <div class="modal-content">

                    <div class="modal-header">
                        <span class="modal-title">{{inputPasswordText.title}}</span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input id='inputPassword' type="password" v-model="inputPassword" class="form-control"/>
                        <div class="invalid-feedback">
                            {{inputPasswordText.errorMsg}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="submit" class="btn btn-primary" v-on:click="submitPassword">{{inputPasswordText.submit}}</button>
                        <button class="btn btn-default" data-dismiss="modal">{{inputPasswordText.close}}</button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import paging from "../other/paging";
    export default {
        name: "exam",
        components: {paging},
        data(){
            return{
                examTitle:'Exam list',
                searchText: '',
                examSubmitText:'提交',
                searchTitle:'Search Exam:',
                examHeadText:{
                    id:'ID',
                    name:'Name',
                    beginTime:'Begin Time',
                    endTime:'End Time',
                    type:'Type',
                    status:'Status'
                },
                type: {
                    0:'Public',
                    1:'Private',
                    2:'Class',
                    3:'Internal'
                },
                exam_list:[],
                currentPage:-1,
                pageTotal:1,
                inputPassword:'',
                currentId:0,
                inputPasswordText:{//模态框绑定文本
                    title:'请输入考试密码',
                    submit:'确定',
                    close:'关闭',
                    errorMsg:'',
                }
            }
        },
        methods: {
            load() {
                this.$refs.page.locking();//加载前上锁
                let len = this.exam_list.length;
                for (let i = 0; i < len; i++) {
                    this.exam_list.splice(0, 1);
                }
                axios.post('/exam',{
                    page: this.currentPage,//当前页号
                    keyword: this.searchText//查询关键字
                }).then(response => {
                    let list = response.data.data;
                    this.pageTotal=response.data.last_page;
                    if(this.currentPage==-1){
                        this.$refs.page.initPage(this.pageTotal);//初始化 自动选择第1页
                    }
                    list.forEach(function (item) {
                        let start_time=new Date(item.start_time);
                        let end_time=new Date(item.end_time);
                        let now=new Date();
                        if(now<start_time){
                            item.status='Pending';
                        }else if(now>end_time){
                            item.status='End';
                        }else{
                            item.status='Running';
                        }
                        this.push(item);
                    }, this.exam_list);
                    this.$refs.page.unLock(); //加载后解锁
                });
            },
            search() {
                this.currentPage=-1;
                this.load();//根据输入考试列表
            },
            getPage:function (page) {
                this.currentPage=page;
                this.load();
                this.$refs.page.goPage();
            },
            enterDetail(id,type){//路由跳转前检查
                this.currentId=id;
                if (this.globalLoginData.islogin==false) {//登陆检查
                    $("#login").modal("show");
                } else if (type==1){//私有比赛检查
                    $("#inputPassword").removeClass('is-invalid');
                    $("#inputModal").modal();
                    $('#inputModal').on('shown.bs.modal',function(e){
                        $('#inputPassword').focus(); //通过ID找到对应输入框，让其获得焦点
                    });
                } else {//成功跳转
                    this.$router.push('/examDetail/' + id);
                }
            },
            submitPassword(){//私有比赛输入密码
                $("#inputPassword").removeClass('is-invalid');
                if (this.inputPassword==''){
                    this.inputPasswordText.errorMsg="密码不能为空！";
                    $("#inputPassword").addClass('is-invalid');
                }else{
                    console.log(this.currentId);
                    //this.$router.push('/examDetail/' + this.currentId);//成功跳转
                    //this.inputPassword=''; //密码错误清空输入框
                }
            }
        },
        mounted() {
            this.load();
        }
    }
</script>

<style scoped>
    .table th,.table td{
        text-align: center;
    }
</style>
