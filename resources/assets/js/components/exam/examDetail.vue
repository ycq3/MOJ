<template>
    <div class="container">
        <!--导航栏-->
        <ul id="examDetailNav" class="nav nav-pills flex-column col-sm-2 h4 float-left">
            <li id="examOverview" class="nav-item">
                <router-link v-bind:to="'/examDetail/'+e_id+'/examOverview'" class="nav-link">{{examText.overview}}</router-link>
            </li>
            <li id="examWritten"  class="nav-item">
                <router-link v-bind:to="'/examDetail/'+e_id+'/written'" class="nav-link">{{examText.written}}</router-link>
            </li>
            <li id="examProblemList" class="nav-item">
                <router-link v-bind:to="'/examDetail/'+e_id+'/examProblemList'" class="nav-link">{{examText.problem}}</router-link>
            </li>
            <li id="examStatus" class="nav-item">
                <router-link v-bind:to="'/examDetail/'+e_id+'/examStatus'" class="nav-link">{{examText.status}}</router-link>
            </li>
            <li id="examRank" class="nav-item">
                <router-link v-bind:to="'/examDetail/'+e_id+'/examRank'" class="nav-link">{{examText.rank}}</router-link>
            </li>
        </ul>

        <transition name="exam"><!--缓存路由显示-->
            <keep-alive>
                <router-view class="col-sm-10 float-right minheight" v-on:examHintMsg="showHintMsg" v-if="$route.meta.keepAlive"></router-view>
            </keep-alive>
        </transition>
        <transition name="exam"><!--不缓存路由显示-->
            <router-view class="col-sm-10 float-right minheight" v-on:examHintMsg="showHintMsg" v-if="!$route.meta.keepAlive"></router-view>
        </transition>

        <div class="clearfix"></div>

        <!--提示框-->
        <div class="modal fade" id="examHint"  role="dialog" aria-labelledby="examHint"
             aria-hidden="true" style="top: 25%">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">

                    <div class="modal-header">
                        <span class="modal-title">系统消息</span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body text-danger">
                        {{examHintInfor}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "examDetail",
        data() {
            return {
                examText: {
                    overview: 'Overview',
                    problem: 'Problem',
                    status: 'Status',
                    rank: 'Rank',
                    written:'Written'
                },
                examRouterName: '',
                e_id: this.$route.params.id,
                examHintInfor:'',
            }
        },
        methods:{
            showHintMsg(msg){
                this.examHintInfor=msg;
                $("#examHint").modal();
            }
        },
        watch: {
            examRouterName: function (newVal, oldVal) {
                var patt1 = new RegExp(newVal);
                $("#examDetailNav>li").each(function (index, element) {//routerName改变 修改导航栏激活
                    var html = $(this).html();
                    if (patt1.test(html) == true) {
                        $(this).children().css({"color": "#fff", "background-color": "#007bff"});
                    } else {
                        $(this).children().css({"color": "#007bff", "background-color": "transparent"});
                    }
                });
            },
            '$route.path': function (newVal, oldVal) {//路由改变修改routerName
                var index = newVal.lastIndexOf("\/");
                var p = newVal.substring(index + 1, newVal.length);
                var pat = new RegExp("examProblem");
                if (pat.test(newVal))
                    p = "examProblemList";
                this.examRouterName = p;
            }
        },
        created() {//导航栏默认激活
            var defaultpath = this.$route.path;
            var index = defaultpath.lastIndexOf("\/");
            var p = defaultpath.substring(index + 1, defaultpath.length);
            var pat = new RegExp("examProblem");
            if (pat.test(defaultpath))
                p = "examProblemList";
            this.examRouterName = p;
        }
    }
</script>

<style scoped>
    .exam-enter-active {
        transition: all .3s ease .6s;
    }

    .exam-leave-active {
        transition: all .5s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }

    .exam-enter, .exam-leave-to {
        transform: translateY(10px);
        opacity: 0;
    }
    .minheight{
        min-height: 550px;
    }
</style>
