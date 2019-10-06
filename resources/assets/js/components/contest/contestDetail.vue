<template>
    <div class="container">
        <!--导航栏-->
        <ul id="contestDetailNav" class="nav nav-pills flex-column h4 col-sm-2 float-left">
            <li id="overview" class="nav-item">
                <router-link v-bind:to="'/contestDetail/'+c_id+'/overview'" class="nav-link">{{contestText.overview}}
                </router-link>
            </li>
            <li id="problemList" class="nav-item">
                <router-link v-bind:to="'/contestDetail/'+c_id+'/problemList'" class="nav-link">
                    {{contestText.problem}}
                </router-link>
            </li>
            <li id="status" class="nav-item">
                <router-link v-bind:to="'/contestDetail/'+c_id+'/status'" class="nav-link">{{contestText.status}}
                </router-link>
            </li>
            <li id="rank" class="nav-item">
                <router-link v-bind:to="'/contestDetail/'+c_id+'/rank'" class="nav-link">{{contestText.rank}}
                </router-link>
            </li>
        </ul>

        <transition name="contest">
            <router-view class="col-sm-10 float-right minheight" v-on:hintMsg="showHintMsg"></router-view>
        </transition>

        <div class="clearfix"></div>

        <!--提示框-->
        <div class="modal fade" id="contestHint" role="dialog" aria-labelledby="contestHint"
             aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">

                    <div class="modal-header">
                        <span class="modal-title">系统消息</span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        {{hintInfor}}
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
        name: "contestDetail",
        data() {
            return {
                contestText: {
                    overview: 'Overview',
                    problem: 'Problem',
                    status: 'Status',
                    rank: 'Rank'
                },
                routerName: '',
                c_id: this.$route.params.id,
                hintInfor:'',
            }
        },
        methods: {
            showHintMsg(msg){
                this.hintInfor=msg;
                $("#contestHint").modal();
            }
        },
        watch: {
            routerName: function (newVal, oldVal) {//routerName变化 改变导航栏激活
                var patt1 = new RegExp(newVal);

                $("#contestDetailNav>li").each(function (index, element) {
                    var html = $(this).html();
                    if (patt1.test(html) == true) {
                        $(this).children().css({"color": "#fff", "background-color": "#007bff"});
                    } else {
                        $(this).children().css({"color": "#007bff", "background-color": "transparent"});
                    }
                });
            },

            '$route.path': function (newVal, oldVal) {//路由变化改变routerName
                var index = newVal.lastIndexOf("\/");
                var p = newVal.substring(index + 1, newVal.length);
                var pat = new RegExp("contestProblem");
                if (pat.test(newVal))
                    p = "problemList";
                this.routerName = p;
            }
        },
        created(){//导航栏默认激活
            var defaultpath=this.$route.path;
            var index=defaultpath.lastIndexOf("\/");
            var p=defaultpath.substring(index+1,defaultpath.length);
            var pat = new RegExp("contestProblem");
            if (pat.test(defaultpath))
                p="problemList";
            this.routerName=p;
        }
    }
</script>

<style scoped>
    .contest-enter-active {
        transition: all .3s ease .6s;
    }

    .contest-leave-active {
        transition: all .5s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }

    .contest-enter, .contest-leave-to {
        transform: translateY(10px);
        opacity: 0;
    }
    .minheight{
        min-height: 550px;
    }
</style>
