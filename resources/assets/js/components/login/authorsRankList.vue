<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <span class="h1">{{authorsRankListTitle}}</span>
            </div>
            <div class="table-responsive card-body" style="overflow-y: hidden">
                <table class="table">
                    <thead>
                    <tr>
                        <th v-for="value in authorsRankHeadText">{{value}}</th>
                    </tr>
                    </thead>

                    <transition-group name="list" tag="tbody">
                        <tr v-for="(item,key) in rank_list" v-bind:key="key" id="rank_list" >
                            <td>{{key+1}}</td>
                            <td>
                                <router-link v-bind:to="'/userInformation/'+item['user_id']" tag="a" target="_blank">{{item['user_name']}}</router-link>
                            </td>
                            <td>{{item['quote']}}</td>
                            <td>{{item['accepted']}}</td>
                            <td>{{item['submitted']}}</td>
                            <td>{{item['submitted']?Math.floor(item['accepted']/item['submitted']*100): '0' }}%</td>
                        </tr>
                    </transition-group>

                </table>
            </div>
            <paging ref="page" class="card-footer" v-on:page-change="getPage" page-id="authorsRankPage"></paging>
        </div>

    </div>
</template>

<script>
    import paging from "../other/paging";
    export default {
        name: "authorsRankList",
        components: {paging},
        data(){
            return {
                authorsRankListTitle:'Authors Ranklist',
                authorsRankHeadText:{
                    rank:'Rank',
                    author:'Author',
                    motto:'Motto',
                    solved:'Solved',
                    submitted:'Submitted',
                    acRatio:'AC Ratio'
                },
                currentPage:-1,
                pageTotal:1,
                rank_list:[]
            }
        },
        methods:{
            getPage:function (page) {
                this.currentPage=page;
                this.$refs.page.goPage();
            },
            load(){
                this.$refs.page.locking();//加载前上锁
                axios.post('/other/authors_rank',{
                    'page':this.currentPage
                }).then(response=>{
                    let list=response.data.data;
                    this.pageTotal=response.data.last_page;
                    if(this.currentPage==-1){
                        this.$refs.page.initPage(this.pageTotal);//初始化 自动选择第1页
                    }
                    while(this.rank_list.pop()){
                    }
                    list.forEach(item=>{
                        this.rank_list.push(item);
                    });
                    this.$refs.page.unLock(); //加载后解锁
                });
            }
        },
        mounted() {
            this.load();
        }
    }
</script>

<style scoped>

</style>
