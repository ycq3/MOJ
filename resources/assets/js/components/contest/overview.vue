<template>
    <div class="container">
        <div class="card">
            <span class="h1 card-header">{{contestInfor.contestTitle}}</span>

            <div class="card-body bg-light">
                <br><br>
                <div>
                    <label class="h3 card-title">BeginTime:&nbsp;<span class="h4 text-info">{{contestInfor.beginTime}}</span>&emsp;</label>
                    <label class="h3 card-title">EndTime:&nbsp;<span class="h4 text-info">{{contestInfor.endTime}}</span> </label>
                </div>
                <div>
                    <label class="h3 card-title">CurrentTime:&nbsp;<span class="h4 text-info">{{contestInfor.currentTime}}</span> </label>
                </div>

                <div class="h4">
                    <label>Type:&nbsp;<span class="h5 text-info">{{contestInfor.type}} &emsp;</span></label>
                    <label>Status:&nbsp;
                        <span class="h5" v-bind:class="contestInfor.status=='Pending'?'text-success':contestInfor.status=='Running'?'text-danger':'text-dark'">
                            {{contestInfor.status}}
                        </span>
                    </label>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        name: "overview",
        data() {
            return {
                contestInfor: {
                    contestTitle: '',
                    beginTime: '',
                    endTime: '',
                    status: '',
                    type: 'Private',
                    currentTime:'',
                },
                type: {
                    0: 'Public',
                    1: 'Private',
                    2: 'Class'
                },
                c_id: this.$route.params.id,
                thisTime: 1,
                thisTimeFlag:false
            }
        },
        watch:{
            thisTime:function () {//根据计时器计算当前时间
                this.contestInfor.currentTime=this.getTime();
                if (this.contestInfor.currentTime < this.contestInfor.beginTime) {
                    this.contestInfor.status = 'Pending';
                } else if (this.contestInfor.currentTime > this.contestInfor.endTime) {
                    this.contestInfor.status = 'End';
                } else {
                    this.contestInfor.status = 'Running';
                }
            }
        },
        created() {
            this.load();
            this.thisTimeFlag=true;//允许计时器计时
        },
        methods: {
            load: function () {
                axios.post('/contest/show/' + this.c_id).then(response => {
                    let data = response.data;
                    if (data.id == this.c_id) {
                        this.contestInfor.contestTitle = data.title;
                        this.contestInfor.beginTime = data.start_time;
                        this.contestInfor.endTime = data.end_time;
                        this.contestInfor.type = this.type[data.type];
                        let start_time = new Date(data.start_time);
                        let end_time = new Date(data.end_time);
                        this.thisTime=data.server_time;
                        this.setCurrentTime();//计时器启动
                        let now = new Date(data.server_time*1000);
                        if (now < start_time) {
                            this.contestInfor.status = 'Pending';
                        } else if (now > end_time) {
                            this.contestInfor.status = 'End';
                        } else {
                            this.contestInfor.status = 'Running';
                        }
                        // alert(new Date(data.server_time*1000)); 时间
                    } else if (data.status == null) {
                        alert('非法请求');
                    } else if (!data.status) {
                        //错误信息
                        alert(data.message);
                    } else {
                        //不知名错误，就比赛ID对不上的情况，理论上不可能
                    }
                }).catch(function(error){
                    let code=error.response.status;
                    if(code == 401){
                        alert('请登录');
                    }else if(code == 404){
                        alert('请求的资源不存在');
                    }
                });
            },
            setCurrentTime(){//计时器走时，计算当前时间
                this.thisTime+=1;
                setTimeout(()=>{
                    if (this.thisTimeFlag)
                        this.setCurrentTime();
                },1000);
            },
            getTime(){
                let date=new Date(this.thisTime*1000);
                let year=date.getFullYear();
                let month=date.getMonth()+1;
                let day=date.getDate();
                let hours=date.getHours();
                let min=date.getMinutes();
                let sec=date.getSeconds();
                if (month<10) month='0'+month;
                if (day<10) day='0'+day;
                if (hours<10) hours='0'+hours;
                if (min<10) min='0'+min;
                if (sec<10) sec='0'+sec;
                return year+'-'+month+'-'+day+' '+hours+':'+min+':'+sec;
            }
        },
        beforeRouteLeave(to, from, next) {//离开页面时检查比赛时间，比赛未开始无法访问rank problem status
            var routeTo = new RegExp("contestDetail");
            if (this.contestInfor.currentTime >= this.contestInfor.beginTime || !routeTo.test(to.path)) {
                this.thisTimeFlag=false;//离开页面计时器停止，防止多个计时器创建
                next();
            } else {
                this.$emit("hintMsg","比赛未开始！");
                next(false);
            }
        }
    }
</script>

<style scoped>

</style>
