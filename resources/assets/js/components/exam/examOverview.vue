<template>
    <div class="container">
        <div class="card">
            <span class="h1 card-header">{{examInfor.title}}</span>

            <div class="card-body bg-light">
                <br><br>
                <div>
                    <label class="h3 card-title">BeginTime:&nbsp;<span class="h4 text-info">{{examInfor.beginTime}}</span>&emsp;</label>
                    <label class="h3 card-title">EndTime:&nbsp;<span class="h4 text-info">{{examInfor.endTime}}</span> </label>
                </div>
                <div>
                    <label class="h3 card-title">CurrentTime:&nbsp;<span class="h4 text-info">{{examInfor.currentTime}}</span> </label>
                </div>
                <div class="h4">
                    <label>Type:&nbsp;<span class="h5 text-info">{{examInfor.type}} &emsp;</span></label>
                    <label>Status:&nbsp;
                        <span class="h5" v-bind:class="examInfor.status=='Pending'?'text-success':examInfor.status=='Running'?'text-danger':'text-dark'">
                            {{examInfor.status}}
                        </span>
                    </label>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        name: "examOverview",
        data() {
            return {
                examInfor: {
                    title: '',
                    beginTime: '',
                    endTime: '',
                    status: '',
                    type: '',
                    currentTime:new Date().toISOString(),
                    hintInfor: '比赛未开始',
                },
                type: {
                    0: 'Public',
                    1: 'Private',
                    2: 'Class'
                },
                e_id: this.$route.params.id,
                thisTime: 1,
                thisTimeFlag:false,
            }
        },
        watch:{
            thisTime:function () {//根据计时器计算当前时间
                this.examInfor.currentTime=this.getTime();
                if (this.examInfor.currentTime < this.examInfor.beginTime) {
                    this.examInfor.status = 'Pending';
                } else if (this.examInfor.currentTime > this.examInfor.endTime) {
                    this.examInfor.status = 'End';
                } else {
                    this.examInfor.status = 'Running';
                }
            }
        },
        created() {
            this.load();
            this.thisTimeFlag=true;//允许计时器计时
        },
        methods: {
            load: function () {
                axios.post('/exam/show/' + this.e_id).then(response => {
                    let data = response.data;
                    if (data.id == this.e_id) {
                        this.examInfor.title=data.title;
                        this.examInfor.beginTime=data.start_time;
                        this.examInfor.endTime=data.end_time;
                        this.examInfor.type=this.type[data.type];
                        let start_time = new Date(data.start_time);
                        let end_time = new Date(data.end_time);
                        this.thisTime=data.server_time;//系统当前时间
                        this.setCurrentTime();//计时器启动
                        let now = new Date();
                        if (now < start_time) {
                            this.examInfor.status = 'Pending';
                        } else if (now > end_time) {
                            this.examInfor.status = 'End';
                        } else {
                            this.examInfor.status = 'Running';
                        }
                    } else if (data.status == null) {
                        alert('非法请求');
                    } else if (!data.status) {
                        //错误信息
                        alert(data.message);
                    }else{
                        //不知名错误，就比赛ID对不上的情况，理论上不可能
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
        beforeRouteLeave(to, from, next) {//离开overview页面进行时间判断，比赛未开始无法进入problem rank status
            var routeTo = new RegExp("examDetail");
            if (this.examInfor.currentTime >= this.examInfor.beginTime || !routeTo.test(to.path)) {
                this.thisTimeFlag=false;//离开页面计时器停止，防止多个计时器创建
                next();
            } else {
                this.$emit("examHintMsg","考试未开始！");
                next(false);
            }
        },
    }
</script>

<style scoped>

</style>
