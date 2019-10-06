<template>
    <div class="container">
        <!--分页导航-->
        <div class="row">
            <ul class="pagination mx-auto" v-bind:id="id">
                <li class="page-item"><a class="page-link disabled" href="javaScript:;" v-on:click="nextPage(currentPage-1)">&laquo;</a></li>
                <li class="page-item" v-for="ind in pageNumber" v-bind:value="ind" v-on:click="nextPage(beginPage+ind-1)">
                    <a class="page-link" href="javaScript:;">{{beginPage+ind-1}}</a>
                </li>
                <li class="page-item"><a class="page-link" href="javaScript:;" v-on:click="nextPage(currentPage+1)">&raquo;</a></li>
            </ul>
        </div>
        <!--跳页输入-->
        <div class="row">
            <div class="input-group mx-auto col-7 col-sm-4">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="pageNumber">{{currentPage+"/"+pageTotal}}</label>
                </div>
                <input id="pageNumber" type="text"  name="points" class="form-control"
                       placeholder="page" v-model="goPageNumber" required/>
                <div class="invalid-tooltip">
                    {{goPageError}}
                </div>
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" v-on:click="nextPage(goPageNumber)">Go</button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "paging",
        props:{
            pageId:String,//作为分页唯一标识
        },
        data(){
            return{
                id:this.pageId,
                pageNumber:5,
                currentPage:1,
                pageTotal:10,
                beginPage:1,
                currentIndex:1,
                goPageNumber:1,
                goPageError:'请输入页数',
                isLock:false
            }
        },
        methods:{
            changePage:function (index) {//改变激活
                this.currentIndex=index;
                $("#pageNumber").removeClass("is-invalid");
                $("#"+this.id+" li").filter(".active").removeClass("active");
                $("#"+this.id+" li").filter(".disabled").removeClass("disabled")
                if(this.currentPage==1) {
                    $("#"+this.id+" li").first().addClass("disabled");
                }
                if(this.currentPage==this.pageTotal){
                    $("#"+this.id+" li").last().addClass("disabled");
                }
                $("#"+this.id+" li").eq(index).addClass("active");
            },
            nextPage:function (newVal) {//换页合法验证
                if (this.isLock){
                    this.$toastr.e("请稍后再试！");return ;
                }
                $("#pageNumber").removeClass("is-invalid");
                var num= new RegExp("^[0-9]*[1-9][0-9]*$");
                if (newVal==""){
                    $("#pageNumber").addClass("is-invalid");
                    this.goPageError="请输入页数！";
                }
                else if(newVal>this.pageTotal||newVal<1){
                    $("#pageNumber").addClass("is-invalid");
                    this.goPageError="超出页数范围！";
                }else if (!num.test(newVal)) {
                    $("#pageNumber").addClass("is-invalid");
                    this.goPageError="输入不合法！";
                }else
                    this.currentPage=newVal;
            },
            goPage:function () {//换页
                var num=this.currentPage;
                var i;
                if (this.pageNumber<5) {
                    this.beginPage=1;
                    i = num-0;
                }
                else {
                    if (num-2<1) {
                        this.beginPage=1;
                        i = num-0;
                    }
                    else if (this.pageTotal-num<2) {
                        this.beginPage=this.pageTotal-4;
                        i=num-this.beginPage+1;
                    }else {
                        this.beginPage=num-2;
                        i=3;
                    }
                }
                this.changePage(i);
            },
            //初始化分页器,传入总页数
            initPage:function(total){
                $("#pageNumber").removeClass("is-invalid");//错误提示初始化
                $("#"+this.id+" li").filter(".active").removeClass("active");
                $("#"+this.id+" li").filter(".disabled").removeClass("disabled")
                total<5?this.pageNumber=total:this.pageNumber=5;
                this.pageTotal=total;
                if (total==0) {
                    this.pageNumber = 1;
                    this.pageTotal=1;
                }
                this.currentPage=1;
                this.beginPage=1;
                this.currentIndex=1;
                this.goPageNumber=1;
                this.goPageError='请输入页数';
                $("#"+this.id+" li").first().addClass("disabled");
                if(this.currentPage==this.pageTotal){
                    $("#"+this.id+" li").last().addClass("disabled");
                }
                $("#"+this.id+" li").eq(1).addClass("active");

            },
            unLock:function () {//解锁
                this.isLock=false;
            },
            locking:function () {//上锁
                this.isLock=true;
            }
        },
        watch:{
            currentPage:function () {
                this.$emit('page-change',this.currentPage);
            }
        }
    }
</script>

<style scoped>

</style>
