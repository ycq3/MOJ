<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>{{userInformationData.id}}-{{userInformationData.name}}</h1>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <h4>Regedit time:{{userInformationData.register_time}}</h4>
                </div>
                <div class="col">
                    <h4>Last submit: {{userInformationData.last_submit}}</h4>
                </div>
            </div>
            <hr>
            <h3>{{userInformationData.quote}}</h3>
            <hr>
            <div class="row">
                <div class="offset-sm-4 offset-2 col-sm-2 text-left col-4">Solved:</div>
                <div class="col-sm-2 text-right col-4">{{userInformationData.accepted}}</div>
            </div>
            <div class="row">
                <div class="offset-sm-4 offset-2 col-sm-2 text-left col-4">Submitted:</div>
                <div class="col-sm-2 text-right col-4">{{userInformationData.submitted}}</div>
            </div>
            <hr>
            <div>
                <div class="col"><h2>List of solved problems</h2></div>
                <div class="col">
                    <ul class="list-unstyled list-inline">
                        <li class="list-inline-item" v-for="(item,index) in userInformationData.pass_list">
                            <router-link v-bind:to="/problemDetail/+index" v-bind:title="item">{{index}}</router-link>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <div>
                <div class="col"><h2>List of unsolved problems</h2></div>
                <div class="col">
                    <ul>
                        <li class="list-inline-item" v-for="(item,index) in userInformationData.fail_list">
                            <router-link v-bind:to="/problemDetail/+index" v-bind:title="item">{{index}}</router-link>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
    export default {
        name: "userInformation",
        data() {
            return {
                userInformationData: {
                    id: '',
                    name: '',
                    register_time: '',
                    last_submit: '',
                    quote: '',
                    accepted: '',
                    submitted: '',
                    pass_list:[],
                    fail_list:[]
                }
            }
        },
        methods: {
            load() {
                axios.post('/other/user_info/'+this.$route.params.id).then(response=>{
                    let data=response.data;
                    this.userInformationData=data;
                });
            }
        },
        mounted(){
            this.load();
        }
    }
</script>

<style scoped>

</style>
