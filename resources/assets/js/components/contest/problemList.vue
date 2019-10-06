<template>
    <div class="container">
        <div class="card">
            <span class="card-header h1">{{listTitle}}</span>

            <div class="table-responsive h5 card-body" style="overflow-y: hidden">
                <br><br>
                <table class="table col text-center">
                    <thead>
                    <tr>
                        <th>{{headText.solved}}</th>
                        <th>{{headText.id}}</th>
                        <th>{{headText.title}}</th>
                        <th>{{headText.ratio}}</th>
                    </tr>
                    </thead>
                    <transition-group name="list" tag="tbody">
                        <tr v-for="item in problem_list" v-bind:key="item['id']" id="contest_list" class="list-item">
                            <td>{{ item['ac_flag']==true?"√":""}}</td>
                            <td>{{item['id']}}</td>
                            <td>
                                <router-link v-bind:to="'/contestDetail/'+c_id+'/contestProblem/'+item['id']">{{item['title']}}</router-link>
                            </td>
                            <td>{{item['rate']}}%{{item['accepted']}}/{{item['submited']}}</td>
                        </tr>
                    </transition-group>
                </table>

            </div>

        </div>

    </div>
</template>

<script>
    export default {
        name: "problemList",
        data(){
            return {
                listTitle:'Problem List',
                headText:{
                    solved:'Solved',
                    id:'ID',
                    title:'Title',
                    ratio:'Ratio(AC/Submit)'
                },
                problem_list:[],
                c_id: this.$route.params.id,
            }
        },
        methods: {
            load() {
                axios.post('/contest/show/'+this.c_id+'/list').then(response => {
                    let list=response.data;
                    list.forEach(function (item) {
                        item['rate']=item['submited']?(item['submited']?item['accepted']/item['submited']*100:0).toFixed(2):0;
                        this.splice(this.length, 0, item);
                    }, this.problem_list);
                });
            },
        },
        created() {
            this.load();
        },
    }
</script>

<style scoped>
    .table th,.table td{
        text-align:center;
    }
</style>
