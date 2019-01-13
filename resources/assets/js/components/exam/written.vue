<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <span class="h1">{{title}}</span>
            </div>
            <div class="card-body text-left">
                <!--选择题div开始-->
                <div class="form-group">
                    <h2>{{problemTypeText.select}}</h2>
                    <ol>
                        <li v-for="item in selectList">
                            <span>{{item.title}}</span><span class="text-danger">&ensp;(分数:{{item.score}})</span>
                            <ul>
                                <li v-for="option in item.content" class="form-inline">
                                    <div class="input-group col">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input v-bind:id="item.note+'-'+option.key" type="radio" v-bind:name="item.note" v-bind:value="option.key"
                                                       v-model="item.data"/>
                                            </div>
                                        </div>
                                        <label class="form-control col" v-bind:for="item.note+'-'+option.key" style="height: auto">{{option.value}}</label>
                                    </div>
                                </li>
                            </ul>
                            <br>
                        </li>
                    </ol>
                </div>
                <!--选择题div结束-->
                <hr>
                <div class="form-group">
                    <h2>{{problemTypeText.judge}}</h2>
                    <ol>
                        <li v-for="item in judgeList">
                            <span>{{item.title}}</span><span class="text-danger">&ensp;(分数:{{item.score}})</span>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="radio" v-bind:name="item.note" value="1" v-model="item.data"/>
                                    </div>
                                </div>
                                <div class="form-control col-5 col-sm-3">true</div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="radio" v-bind:name="item.note" value="0" v-model="item.data"/>
                                    </div>
                                </div>
                                <div class="form-control col-5 col-sm-3">false</div>
                            </div>

                            <br>
                        </li>
                    </ol>
                </div>
                <hr>
                <div class="form-group">
                    <h2>{{problemTypeText.codeFill}}</h2>
                    <ol>
                        <li v-for="item in codeFillList">
                            <span><pre>{{item.title}}</pre></span><span
                                class="text-danger">&ensp;(分数:{{item.score}})</span>
                            <div v-for="(data,index) in item.data" class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{index+1}}</span>
                                </div>
                                <input type="text" class="form-control col-sm-8"
                                       v-model="item.data[index].codeFillData"/>
                            </div>
                            <br>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success" v-on:click="save">{{buttonText.save}}</button>
                <button class="btn btn-primary" v-on:click="submit">{{buttonText.submit}}</button>
            </div>
        </div>

        <!--保存检查提示框-->
        <div class="modal fade" id="checkUp" role="dialog" aria-labelledby="checkUp"
             aria-hidden="true">
            <div class="modal-dialog modal-sm" style="top: 20%;">
                <div class="modal-content">

                    <div class="modal-header">
                        <span class="modal-title">系统消息</span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div><span class="text-success">选择题总数:{{selectList.length}}</span> <span>&nbsp;完成题数:{{checkUp.select}}</span>
                        </div>
                        <hr>
                        <div><span class="text-success">判断题总数:{{selectList.length}}</span> <span>&nbsp;完成题数:{{checkUp.judge}}</span>
                        </div>
                        <hr>
                        <div><span class="text-success">程序填空题总数:{{checkUp.codeFillTotal}}</span> <span>&nbsp;完成题数:{{checkUp.codeFill}}</span>
                        </div>
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
        name: "written",
        data() {
            return {
                title: 'Written',
                problemTypeText: {
                    select: '选择题',
                    judge: '判断题',
                    codeFill: '程序填空'
                },
                hint: '提交前请保存作答！',
                buttonText: {
                    save: '保存答案',
                    submit: '提交答案'
                },
                selectList: [],
                judgeList: [
                    {note: 'j1', title: '判断题1', score: '5', data: ''},
                    {note: 'j2', title: '判断题2', score: '10', data: ''}
                ],
                codeFillList: [
                    {
                        note: 'c1', title: '#include<iostream>\n' +
                        'using namespace std;\n' +
                        'int main(){\n' +
                        '    int a,b,c;\n' +
                        '    while(~scanf("%d%d%d",&a,&b,&c)){\n' +
                        '        printf("%d\\n",a+b+c);\n' +
                        '        1:___;\n' +
                        '        2:___;\n' +
                        '        3:___;\n' +
                        '    }\n' +
                        '}', score: '5', data: [{codeFillData: ''}, {codeFillData: ''}, {codeFillData: ''}]
                    }
                ],
                writtenData: {
                    selectData: [],
                    judgeData: [],
                    codeFillData: []
                },
                checkUp: {
                    select: 0,
                    judge: 0,
                    codeFill: 0,
                    codeFillTotal: 0
                },
                e_id: this.$route.params.id,
            }
        },
        methods: {
            save() {
                this.writtenData = {
                    selectData: [],
                    judgeData: [],
                    codeFillData: []
                };
                this.checkUp = {
                    select: 0,
                    judge: 0,
                    codeFill: 0,
                    codeFillTotal: 0
                };
                for (let item in this.selectList) {//选择题保存
                    let t = {
                        num: this.selectList[item].note,
                        data: this.selectList[item].data
                    };
                    if (t.data != '') this.checkUp.select++;
                    this.writtenData.selectData.push(t);
                }
                for (let item in this.judgeList) {//判断题保存
                    let t = {
                        num: item,
                        data: this.judgeList[item].data
                    };
                    if (t.data != '') this.checkUp.judge++;
                    this.writtenData.judgeData.push(t);
                }
                for (let item in this.codeFillList) {//代码填空保存
                    let t = {
                        num: item,
                        data: this.codeFillList[item].data
                    };
                    for (let d in t.data) {
                        if (t.data[d].codeFillData != '')
                            this.checkUp.codeFill++;
                        this.checkUp.codeFillTotal++;
                    }
                    this.writtenData.codeFillData.push(t);
                }
                this.$toastr.s("保存成功！");
                $("#checkUp").modal();
            },
            submit() {
                this.$toastr.s("提交成功！");
                console.log(this.writtenData);
            },
            load() {
                axios.post('/exam/written/' + this.e_id + '/show').then(response => {
                    let data = response.data;
                    data.forEach(item => {
                        if(item.type==1){
                            let content=[];
                            let size=item.content.length;
                            item.content.forEach((option,key)=>{
                                content.push({'key':(key+item.fake)%size,'value': option});
                            });
                            content.sort(function () {
                                return 0.5 - Math.random()
                            });
                            let node={note:item.id,title:item.title,content:content,score:item.score,data:''};
                            this.selectList.push(node);
                        }
                    });
                });
                this.selectList.sort(function () {
                    return 0.5 - Math.random()
                });

            }
        },
        mounted() {
            this.load();
            this.$emit("examHintMsg", "提交前请保存作答！");
        },

    }
</script>

<style scoped>

</style>