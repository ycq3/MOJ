<template>
    <div class="container">
        <div class="card">
            <span class="h1 card-header">{{examListTitle}}</span>

            <div class="table-responsive h5 card-body" style="overflow-y: hidden">
                <br><br>
                <table class="table">
                    <thead>
                    <tr>
                        <th v-for="item in examHeadText">{{item}}</th>
                    </tr>
                    </thead>
                    <transition-group name="list" tag="tbody">
                        <tr v-for="item in problem_list" v-bind:key="item['id']" id="contest_list" class="list-item">
                            <td>{{item['user_score']}}</td>
                            <td>{{item['score']}}</td>
                            <td>{{item['id']}}</td>
                            <td>
                                <router-link v-bind:to="'/examDetail/'+e_id+'/examProblem/'+item['id']">{{item['title']}}</router-link>
                            </td>
                            <td>{{item['difficulty']}}</td>
                        </tr>
                    </transition-group>
                </table>

            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "examProblemList",
        data() {
            return {
                examListTitle: 'Problem List',
                examHeadText: {
                    score: 'Your Score',
                    totalScore:'Total Score',
                    id: 'ID',
                    title: 'Title',
                    difficulty: 'Difficulty'
                },
                problem_list:[],
                e_id: this.$route.params.id,
            }
        },
        methods: {
            load() {
                axios.post('/exam/show/'+this.e_id+'/list').then(response => {
                    let list=response.data;
                    list.forEach(item=> {
                        item['rate'] = item['submited'] ? (item['submited'] ? item['accepted'] / item['submited'] * 100 : 0).toFixed(2) : 0;
                        item['id'] = item['exam_problem_type_id'];
                        this.problem_list.push(item);
                    });
                });
            },
        },
        created() {
            this.load();
        },
    }
</script>

<style scoped>

</style>
