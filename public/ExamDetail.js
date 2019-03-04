webpackJsonp([0],{293:function(t,e,a){var i=a(294);"string"==typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);a(20)("4f02ac71",i,!0,{})},294:function(t,e,a){(t.exports=a(7)(!1)).push([t.i,".exam-enter-active[data-v-eb4ed7d8]{-webkit-transition:all .3s ease .6s;transition:all .3s ease .6s}.exam-leave-active[data-v-eb4ed7d8]{-webkit-transition:all .5s cubic-bezier(1,.5,.8,1);transition:all .5s cubic-bezier(1,.5,.8,1)}.exam-enter[data-v-eb4ed7d8],.exam-leave-to[data-v-eb4ed7d8]{-webkit-transform:translateY(10px);transform:translateY(10px);opacity:0}.minheight[data-v-eb4ed7d8]{min-height:550px}",""])},295:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={name:"examDetail",data:function(){return{examText:{overview:"Overview",problem:"Problem",status:"Status",rank:"Rank",written:"Written"},examRouterName:"",e_id:this.$route.params.id,examHintInfor:""}},methods:{showHintMsg:function(t){this.examHintInfor=t,$("#examHint").modal()}},watch:{examRouterName:function(t,e){var a=new RegExp(t);$("#examDetailNav>li").each(function(t,e){var i=$(this).html();1==a.test(i)?$(this).children().css({color:"#fff","background-color":"#007bff"}):$(this).children().css({color:"#007bff","background-color":"transparent"})})},"$route.path":function(t,e){var a=t.lastIndexOf("/"),i=t.substring(a+1,t.length);new RegExp("examProblem").test(t)&&(i="examProblemList"),this.examRouterName=i}},created:function(){var t=this.$route.path,e=t.lastIndexOf("/"),a=t.substring(e+1,t.length);new RegExp("examProblem").test(t)&&(a="examProblemList"),this.examRouterName=a}}},296:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"container"},[a("ul",{staticClass:"nav nav-pills flex-column col-sm-2 h4 float-left",attrs:{id:"examDetailNav"}},[a("li",{staticClass:"nav-item",attrs:{id:"examOverview"}},[a("router-link",{staticClass:"nav-link",attrs:{to:"/examDetail/"+t.e_id+"/examOverview"}},[t._v(t._s(t.examText.overview))])],1),t._v(" "),a("li",{staticClass:"nav-item",attrs:{id:"examWritten"}},[a("router-link",{staticClass:"nav-link",attrs:{to:"/examDetail/"+t.e_id+"/written"}},[t._v(t._s(t.examText.written))])],1),t._v(" "),a("li",{staticClass:"nav-item",attrs:{id:"examProblemList"}},[a("router-link",{staticClass:"nav-link",attrs:{to:"/examDetail/"+t.e_id+"/examProblemList"}},[t._v(t._s(t.examText.problem))])],1),t._v(" "),a("li",{staticClass:"nav-item",attrs:{id:"examStatus"}},[a("router-link",{staticClass:"nav-link",attrs:{to:"/examDetail/"+t.e_id+"/examStatus"}},[t._v(t._s(t.examText.status))])],1),t._v(" "),a("li",{staticClass:"nav-item",attrs:{id:"examRank"}},[a("router-link",{staticClass:"nav-link",attrs:{to:"/examDetail/"+t.e_id+"/examRank"}},[t._v(t._s(t.examText.rank))])],1)]),t._v(" "),a("transition",{attrs:{name:"exam"}},[a("keep-alive",[t.$route.meta.keepAlive?a("router-view",{staticClass:"col-sm-10 float-right minheight",on:{examHintMsg:t.showHintMsg}}):t._e()],1)],1),t._v(" "),a("transition",{attrs:{name:"exam"}},[t.$route.meta.keepAlive?t._e():a("router-view",{staticClass:"col-sm-10 float-right minheight",on:{examHintMsg:t.showHintMsg}})],1),t._v(" "),a("div",{staticClass:"clearfix"}),t._v(" "),a("div",{staticClass:"modal fade",staticStyle:{top:"25%"},attrs:{id:"examHint",role:"dialog","aria-labelledby":"examHint","aria-hidden":"true"}},[a("div",{staticClass:"modal-dialog modal-sm"},[a("div",{staticClass:"modal-content"},[t._m(0),t._v(" "),a("div",{staticClass:"modal-body text-danger"},[t._v("\n                    "+t._s(t.examHintInfor)+"\n                ")]),t._v(" "),t._m(1)])])])],1)},staticRenderFns:[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-header"},[e("span",{staticClass:"modal-title"},[this._v("系统消息")]),this._v(" "),e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal"}},[this._v("×")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-footer"},[e("button",{staticClass:"btn btn-default",attrs:{type:"button","data-dismiss":"modal"}},[this._v("关闭")])])}]}},297:function(t,e,a){var i=a(298);"string"==typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);a(20)("063dd551",i,!0,{})},298:function(t,e,a){(t.exports=a(7)(!1)).push([t.i,"",""])},299:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={name:"examOverview",data:function(){return{examInfor:{title:"",beginTime:"",endTime:"",status:"",type:"",currentTime:(new Date).toISOString(),hintInfor:"比赛未开始"},type:{0:"Public",1:"Private",2:"Class"},e_id:this.$route.params.id,thisTime:1,thisTimeFlag:!1}},watch:{thisTime:function(){this.examInfor.currentTime=this.getTime(),this.examInfor.currentTime<this.examInfor.beginTime?this.examInfor.status="Pending":this.examInfor.currentTime>this.examInfor.endTime?this.examInfor.status="End":this.examInfor.status="Running"}},created:function(){this.load(),this.thisTimeFlag=!0},methods:{load:function(){var t=this;axios.post("/exam/show/"+this.e_id).then(function(e){var a=e.data;if(a.id==t.e_id){t.examInfor.title=a.title,t.examInfor.beginTime=a.start_time,t.examInfor.endTime=a.end_time,t.examInfor.type=t.type[a.type];var i=new Date(a.start_time),s=new Date(a.end_time);t.thisTime=a.server_time,t.setCurrentTime();var n=new Date;t.examInfor.status=n<i?"Pending":n>s?"End":"Running"}else null==a.status?alert("非法请求"):a.status||alert(a.message)})},setCurrentTime:function(){var t=this;this.thisTime+=1,setTimeout(function(){t.thisTimeFlag&&t.setCurrentTime()},1e3)},getTime:function(){var t=new Date(1e3*this.thisTime),e=t.getFullYear(),a=t.getMonth()+1,i=t.getDate(),s=t.getHours(),n=t.getMinutes(),r=t.getSeconds();return a<10&&(a="0"+a),i<10&&(i="0"+i),s<10&&(s="0"+s),n<10&&(n="0"+n),r<10&&(r="0"+r),e+"-"+a+"-"+i+" "+s+":"+n+":"+r}},beforeRouteLeave:function(t,e,a){var i=new RegExp("examDetail");this.examInfor.currentTime>=this.examInfor.beginTime||!i.test(t.path)?(this.thisTimeFlag=!1,a()):(this.$emit("examHintMsg","考试未开始！"),a(!1))}}},300:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"container"},[a("div",{staticClass:"card"},[a("span",{staticClass:"h1 card-header"},[t._v(t._s(t.examInfor.title))]),t._v(" "),a("div",{staticClass:"card-body bg-light"},[a("br"),a("br"),t._v(" "),a("div",[a("label",{staticClass:"h3 card-title"},[t._v("BeginTime: "),a("span",{staticClass:"h4 text-info"},[t._v(t._s(t.examInfor.beginTime))]),t._v(" ")]),t._v(" "),a("label",{staticClass:"h3 card-title"},[t._v("EndTime: "),a("span",{staticClass:"h4 text-info"},[t._v(t._s(t.examInfor.endTime))])])]),t._v(" "),a("div",[a("label",{staticClass:"h3 card-title"},[t._v("CurrentTime: "),a("span",{staticClass:"h4 text-info"},[t._v(t._s(t.examInfor.currentTime))])])]),t._v(" "),a("div",{staticClass:"h4"},[a("label",[t._v("Type: "),a("span",{staticClass:"h5 text-info"},[t._v(t._s(t.examInfor.type)+"  ")])]),t._v(" "),a("label",[t._v("Status: \n                    "),a("span",{staticClass:"h5",class:"Pending"==t.examInfor.status?"text-success":"Running"==t.examInfor.status?"text-danger":"text-dark"},[t._v("\n                        "+t._s(t.examInfor.status)+"\n                    ")])])])])])])},staticRenderFns:[]}},356:function(t,e,a){var i=a(3)(a(295),a(296),!1,function(t){a(293)},"data-v-eb4ed7d8",null);t.exports=i.exports},357:function(t,e,a){var i=a(3)(a(299),a(300),!1,function(t){a(297)},"data-v-cfb896fe",null);t.exports=i.exports}});