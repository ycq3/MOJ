import Vue from 'vue'
import VueRouter from 'vue-router'

// vue-easytable
import 'vue-easytable/libs/themes-base/index.css'
// 导入 table 和 分页组件
import {VTable, VPagination} from 'vue-easytable'
// 注册到全局
Vue.component(VTable.name, VTable);
Vue.component(VPagination.name, VPagination);

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        {
            path:'/',
            redirect:'/home'
        },
        {
            path:'/home',
            name:'Home',
            component:() => import(/* webpackChunkName: "Home" */ '../components/home/home')
        },
        {
            path:'/problem',
            name:'Problem',
            component:() => import(/* webpackChunkName: "Problem" */ '../components/problem/problem')
        },
        {
            path:'/contest',
            name:'Contest',
            component:() => import(/* webpackChunkName: "Contest" */ '../components/contest/contest')
        },
        {
            path:'/exam',
            name:'Exam',
            component:() => import(/* webpackChunkName: "Exam" */ '../components/exam/exam')
        },
        {
            path:'/problemDetail/:id',
            name:'ProblemDetail',
            component:() => import(/* webpackChunkName: "ProblemDetail" */ '../components/problem/problemDetail')
        },
        {
            path:'/contestDetail/:id',
            name:'ContestDetail',
            component:() => import(/* webpackChunkName: "ContestDetail" */ '../components/contest/contestDetail'),
            redirect:'/contestDetail/:id/overview',
            children:[
                {
                    path:'overview',
                    name:'Overview',
                    component:() => import(/* webpackChunkName: "ContestDetail" */ '../components/contest/overview')
                },
                {
                    path:'problemList',
                    name:'ProblemList',
                    component:() => import(/* webpackChunkName: "ProblemList" */ '../components/contest/problemList')
                },
                {
                    path:'status',
                    name:'Status',
                    component:() => import(/* webpackChunkName: "Status" */ '../components/contest/status')
                },
                {
                    path:'rank',
                    name:'Rank',
                    component:() => import(/* webpackChunkName: "Rank" */ '../components/contest/rank')
                },
                {
                    path:'contestProblem/:p_id',
                    name:'ContestProblem',
                    component:() => import(/* webpackChunkName: "ContestProblem" */ '../components/contest/contestProblemDetail')
                },
            ]
        },
        {
            path:'/modify',
            name:'Modify',
            component:() => import(/* webpackChunkName: "Modify" */ '../components/login/modify')
        },
        {
            path:'/userInformation/:id',
            name:'UserInformation',
            component:() => import(/* webpackChunkName: "UserInformation" */ '../components/login/userInformation')
        },
        {
            path:'/examDetail/:id',
            name:'ExamDetail',
            component:() => import(/* webpackChunkName: "ExamDetail" */ '../components/exam/examDetail'),
            redirect:'/examDetail/:id/examOverview',
            children:[
                {
                    path:'examOverview',
                    name:'ExamOverview',
                    meta: {keepAlive: false},
                    component:() => import(/* webpackChunkName: "ExamDetail" */ '../components/exam/examOverview')
                },
                {
                    path:'examProblemList',
                    name:'ExamProblemList',
                    meta: {keepAlive: false},
                    component:() => import(/* webpackChunkName: "ExamProblemList" */ '../components/exam/examProblemList')
                },
                {
                    path:'examProblem/:p_id',
                    name:'ExamProblem',
                    meta: {keepAlive: true},
                    component:() => import(/* webpackChunkName: "ExamProblem" */ '../components/exam/examProblemDetail')
                },
                {
                    path:'examStatus',
                    name:'ExamStatus',
                    meta: {keepAlive: false},
                    component:() => import(/* webpackChunkName: "ExamStatus" */ '../components/exam/examStatus')
                },
                {
                    path:'examRank',
                    name:'ExamRank',
                    meta: {keepAlive: false},
                    component:() => import(/* webpackChunkName: "ExamRank" */ '../components/exam/examRank')
                },
                {
                    path:'written',
                    name:'Written',
                    meta: {keepAlive: true},
                    component:() => import(/* webpackChunkName: "Written" */ '../components/exam/written')
                }
            ]
        },
        {
            path:'/authorsRankList',
            name:'AuthorsRankList',
            component:() => import(/* webpackChunkName: "AuthorsRankList" */ '../components/login/authorsRankList')
        },
        {
            path:'/register',
            mame:'Register',
            component:() => import(/* webpackChunkName: "Register" */ '../components/login/register')
        },
        {
            path:'/viewCode/:id',
            name:'ViewCode',
            component:() => import(/* webpackChunkName: "ViewCode" */ '../components/other/viewCode')
        },
        {
            path:'/problemStatus/:id',
            name:'ProblemStatus',
            component:() => import(/* webpackChunkName: "ProblemStatus" */ '../components/problem/problemStatus')
        },
        {
            path:'/examViewCode/:id',
            name:'ExamViewCode',
            component:() => import(/* webpackChunkName: "ExamViewCode" */ '../components/exam/examViewCode')
        }
    ]
})
