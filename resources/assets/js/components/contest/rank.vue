<template>
    <div class="container">
        <div class="card">
            <span class="h1 card-header">{{rankTitle}}</span>

            <div v-if="columns.length>0 && submitData.length>0" key="easyTable" class="card-body">
                <br>
                <!--easytable插件  http://doc.huangsw.com/vue-easytable/app.html#/intro -->
                <v-table
                        is-horizontal-resize
                        style="width:100%"
                        :columns="columns"
                        :table-data="tableData"
                        :column-cell-class-name="columnCellClass"
                ></v-table>
            </div>
            <div v-else class="card-body h2 text-info">Loading . . .</div>
        </div>


    </div>
</template>

<script>
    export default {
        name: "rank",
        data() {
            return {
                rankTitle: 'Rank List',
                columns: [],//表头
                tableData: [//数据
                    // {rank:'2',nickname:'aa',solved:'2',penalty:'200',1:'201:43:19 (-1)',2:'270:18:50',4:'',5:''},
                    //{rank:'2',nickname:'aa',solved:'2',penalty:'200',},
                ],
                submitData: [],//提交情况 2首A  1Ac 0无提交 -1错误  按照rank排名向下排{1:'2',2:'1',3:'0',4:'2',5:'-1'}
                c_id: this.$route.params.id,
            }
        },
        created() {
            this.load();
        },
        methods: {
            columnCellClass(rowIndex, columnName, rowData) {// 设置单元格样式函数
                if (this.submitData[rowIndex][columnName] == 2) { //首A
                    return 'column-cell-class-name-firstAccept'
                }
                if (this.submitData[rowIndex][columnName] == 1) { //AC
                    return 'column-cell-class-name-accept'
                }
                if (this.submitData[rowIndex][columnName] == -1) { //eror
                    return 'column-cell-class-name-error'
                }
            },
            load() {
                axios.post('/contest/rank/' + this.c_id).then(response => {
                    let a = [{
                        field: 'rank',
                        title: 'Rank',
                        width: 50,
                        titleAlign: 'center',
                        columnAlign: 'center',
                        isFrozen: true,
                        isResize: true
                    },
                        {
                            field: 'nickname',
                            title: 'Nickname',
                            width: 100,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            isFrozen: true,
                            isResize: true
                        },
                        {
                            field: 'solved',
                            title: 'Solved',
                            width: 60,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            isFrozen: true,
                            isResize: true
                        },
                        {
                            field: 'penalty',
                            title: 'Penalty',
                            width: 100,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            isResize: true
                        },];
                    let data = response.data;
                    data.problem_id.forEach((value, key) => {
                        a.push({
                            field: value,
                            title: value,
                            width: 100,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            isResize: true
                        });
                    });
                    function time(second) {
                        return Math.floor(second / 3600) + ':' + Math.floor(second % 3600 / 60) + ':' + Math.floor(second % 60)
                    }
                    data.rank.forEach((value, key) => {
                        let row = {
                            'rank': key + 1,
                            nickname: value.user_name,
                            solved: value.accept,
                            penalty: time(value.penalty)
                        };
                        let row_data = {};
                        for (let item in value.problem_data) {
                            row[item] = time(value.problem_data[item].time) + ' ' + (value.problem_data[item].fail != 0 ? ('(' + value.problem_data[item].fail + ')') : '');
                            row_data[item] = value.problem_data[item].result ? '1' : '-1';
                            data.ak[item]&&data.ak[item].user_id == value.user_id && (row_data[item] = '2');
                        }
                        this.tableData.push(row);
                        this.submitData.push(row_data);
                    });
                    this.columns = this.columns.concat(a);
                });
            }
        }

    }
</script>

<style>
    .v-table-title-class {
        background-color: #8cc6ff;
        color: #4c4c4c;
        font-weight: bold;
    }

    .column-cell-class-name-firstAccept {
        background-color: #00ff99;
        color: white;
    }

    .column-cell-class-name-accept {
        background-color: green;
        color: white;
    }

    .column-cell-class-name-error {
        background-color: red;
        color: white;
    }
</style>
