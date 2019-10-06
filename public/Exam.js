webpackJsonp([11],{220:function(t,a,e){var s=e(3)(e(223),e(224),!1,function(t){e(221)},"data-v-2bf152ba",null);t.exports=s.exports},221:function(t,a,e){var s=e(222);"string"==typeof s&&(s=[[t.i,s,""]]),s.locals&&(t.exports=s.locals);e(20)("f022f530",s,!0,{})},222:function(t,a,e){(t.exports=e(7)(!1)).push([t.i,"",""])},223:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default={name:"paging",props:{pageId:String},data:function(){return{id:this.pageId,pageNumber:5,currentPage:1,pageTotal:10,beginPage:1,currentIndex:1,goPageNumber:1,goPageError:"请输入页数",isLock:!1}},methods:{changePage:function(t){this.currentIndex=t,$("#pageNumber").removeClass("is-invalid"),$("#"+this.id+" li").filter(".active").removeClass("active"),$("#"+this.id+" li").filter(".disabled").removeClass("disabled"),1==this.currentPage&&$("#"+this.id+" li").first().addClass("disabled"),this.currentPage==this.pageTotal&&$("#"+this.id+" li").last().addClass("disabled"),$("#"+this.id+" li").eq(t).addClass("active")},nextPage:function(t){if(this.isLock)this.$toastr.e("请稍后再试！");else{$("#pageNumber").removeClass("is-invalid");var a=new RegExp("^[0-9]*[1-9][0-9]*$");""==t?($("#pageNumber").addClass("is-invalid"),this.goPageError="请输入页数！"):t>this.pageTotal||t<1?($("#pageNumber").addClass("is-invalid"),this.goPageError="超出页数范围！"):a.test(t)?this.currentPage=t:($("#pageNumber").addClass("is-invalid"),this.goPageError="输入不合法！")}},goPage:function(){var t,a=this.currentPage;this.pageNumber<5?(this.beginPage=1,t=a-0):a-2<1?(this.beginPage=1,t=a-0):this.pageTotal-a<2?(this.beginPage=this.pageTotal-4,t=a-this.beginPage+1):(this.beginPage=a-2,t=3),this.changePage(t)},initPage:function(t){$("#pageNumber").removeClass("is-invalid"),$("#"+this.id+" li").filter(".active").removeClass("active"),$("#"+this.id+" li").filter(".disabled").removeClass("disabled"),this.pageNumber=t<5?t:5,this.pageTotal=t,0==t&&(this.pageNumber=1,this.pageTotal=1),this.currentPage=1,this.beginPage=1,this.currentIndex=1,this.goPageNumber=1,this.goPageError="请输入页数",$("#"+this.id+" li").first().addClass("disabled"),this.currentPage==this.pageTotal&&$("#"+this.id+" li").last().addClass("disabled"),$("#"+this.id+" li").eq(1).addClass("active")},unLock:function(){this.isLock=!1},locking:function(){this.isLock=!0}},watch:{currentPage:function(){this.$emit("page-change",this.currentPage)}}}},224:function(t,a){t.exports={render:function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"container"},[e("div",{staticClass:"row"},[e("ul",{staticClass:"pagination mx-auto",attrs:{id:t.id}},[e("li",{staticClass:"page-item"},[e("a",{staticClass:"page-link disabled",attrs:{href:"javaScript:;"},on:{click:function(a){t.nextPage(t.currentPage-1)}}},[t._v("«")])]),t._v(" "),t._l(t.pageNumber,function(a){return e("li",{staticClass:"page-item",attrs:{value:a},on:{click:function(e){t.nextPage(t.beginPage+a-1)}}},[e("a",{staticClass:"page-link",attrs:{href:"javaScript:;"}},[t._v(t._s(t.beginPage+a-1))])])}),t._v(" "),e("li",{staticClass:"page-item"},[e("a",{staticClass:"page-link",attrs:{href:"javaScript:;"},on:{click:function(a){t.nextPage(t.currentPage+1)}}},[t._v("»")])])],2)]),t._v(" "),e("div",{staticClass:"row"},[e("div",{staticClass:"input-group mx-auto col-7 col-sm-4"},[e("div",{staticClass:"input-group-prepend"},[e("label",{staticClass:"input-group-text",attrs:{for:"pageNumber"}},[t._v(t._s(t.currentPage+"/"+t.pageTotal))])]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:t.goPageNumber,expression:"goPageNumber"}],staticClass:"form-control",attrs:{id:"pageNumber",type:"text",name:"points",placeholder:"page",required:""},domProps:{value:t.goPageNumber},on:{input:function(a){a.target.composing||(t.goPageNumber=a.target.value)}}}),t._v(" "),e("div",{staticClass:"invalid-tooltip"},[t._v("\n                "+t._s(t.goPageError)+"\n            ")]),t._v(" "),e("div",{staticClass:"input-group-append"},[e("button",{staticClass:"btn btn-primary",attrs:{type:"button"},on:{click:function(a){t.nextPage(t.goPageNumber)}}},[t._v("Go")])])])])])},staticRenderFns:[]}},247:function(t,a,e){var s=e(248);"string"==typeof s&&(s=[[t.i,s,""]]),s.locals&&(t.exports=s.locals);e(20)("607ee326",s,!0,{})},248:function(t,a,e){(t.exports=e(7)(!1)).push([t.i,".table td[data-v-57089cf2],.table th[data-v-57089cf2]{text-align:center}",""])},249:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var s=e(220),i=e.n(s);a.default={name:"exam",components:{paging:i.a},data:function(){return{examTitle:"Exam list",searchText:"",examSubmitText:"提交",searchTitle:"Search Exam:",examHeadText:{id:"ID",name:"Name",beginTime:"Begin Time",endTime:"End Time",type:"Type",status:"Status"},type:{0:"Public",1:"Private",2:"Class",3:"Internal"},exam_list:[],currentPage:-1,pageTotal:1,inputPassword:"",currentId:0,inputPasswordText:{title:"请输入考试密码",submit:"确定",close:"关闭",errorMsg:""}}},methods:{load:function(){var t=this;this.$refs.page.locking();for(var a=this.exam_list.length,e=0;e<a;e++)this.exam_list.splice(0,1);axios.post("/exam",{page:this.currentPage,keyword:this.searchText}).then(function(a){var e=a.data.data;t.pageTotal=a.data.last_page,-1==t.currentPage&&t.$refs.page.initPage(t.pageTotal),e.forEach(function(t){var a=new Date(t.start_time),e=new Date(t.end_time),s=new Date;t.status=s<a?"Pending":s>e?"End":"Running",this.push(t)},t.exam_list),t.$refs.page.unLock()})},search:function(){this.currentPage=-1,this.load()},getPage:function(t){this.currentPage=t,this.load(),this.$refs.page.goPage()},enterDetail:function(t,a){this.currentId=t,0==this.globalLoginData.islogin?$("#login").modal("show"):1==a?($("#inputPassword").removeClass("is-invalid"),$("#inputModal").modal(),$("#inputModal").on("shown.bs.modal",function(t){$("#inputPassword").focus()})):this.$router.push("/examDetail/"+t)},submitPassword:function(){$("#inputPassword").removeClass("is-invalid"),""==this.inputPassword?(this.inputPasswordText.errorMsg="密码不能为空！",$("#inputPassword").addClass("is-invalid")):console.log(this.currentId)}},mounted:function(){this.load()}}},250:function(t,a){t.exports={render:function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"container"},[e("div",{staticClass:"card"},[e("div",{staticClass:"card-header"},[e("div",{staticClass:"row"},[e("h1",{staticClass:"col text-center"},[t._v(t._s(t.examTitle))])]),t._v(" "),e("div",{staticClass:"row form-inline"},[e("div",{staticClass:"col text-center"},[e("strong",[t._v(t._s(t.searchTitle))]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:t.searchText,expression:"searchText"}],staticClass:"form-control",attrs:{type:"text",placeholder:"请输入考试题目"},domProps:{value:t.searchText},on:{input:function(a){a.target.composing||(t.searchText=a.target.value)}}}),t._v(" "),e("button",{staticClass:"btn btn-default",on:{click:t.search}},[t._v(t._s(t.examSubmitText))])])])]),t._v(" "),e("div",{staticClass:"table-responsive card-body",staticStyle:{"overflow-y":"hidden"}},[e("table",{staticClass:"table"},[e("thead",[e("tr",t._l(t.examHeadText,function(a){return e("th",[t._v(t._s(a))])}))]),t._v(" "),e("transition-group",{attrs:{name:"list",tag:"tbody"}},t._l(t.exam_list,function(a){return e("tr",{key:a.id,staticClass:"list-item",attrs:{id:"exam_list"}},[e("td",[t._v(t._s(a.id))]),t._v(" "),e("td",[e("a",{attrs:{href:"javaScript:;"},on:{click:function(e){t.enterDetail(a.id,a.type)}}},[t._v(t._s(a.title))])]),t._v(" "),e("td",[t._v(t._s(a.start_time))]),t._v(" "),e("td",[t._v(t._s(a.end_time))]),t._v(" "),e("td",[t._v(t._s(t.type[a.type]))]),t._v(" "),e("td",[t._v(t._s(a.status))])])}))],1)]),t._v(" "),e("paging",{ref:"page",staticClass:"card-footer",attrs:{"page-id":"examPage"},on:{"page-change":t.getPage}})],1),t._v(" "),e("div",{staticClass:"modal fade",attrs:{id:"inputModal",role:"dialog","aria-labelledby":"inputModal","aria-hidden":"true"}},[e("div",{staticClass:"modal-dialog modal-sm",staticStyle:{top:"25%"}},[e("div",{staticClass:"modal-content"},[e("div",{staticClass:"modal-header"},[e("span",{staticClass:"modal-title"},[t._v(t._s(t.inputPasswordText.title))]),t._v(" "),e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal"}},[t._v("×")])]),t._v(" "),e("div",{staticClass:"modal-body"},[e("input",{directives:[{name:"model",rawName:"v-model",value:t.inputPassword,expression:"inputPassword"}],staticClass:"form-control",attrs:{id:"inputPassword",type:"password"},domProps:{value:t.inputPassword},on:{input:function(a){a.target.composing||(t.inputPassword=a.target.value)}}}),t._v(" "),e("div",{staticClass:"invalid-feedback"},[t._v("\n                        "+t._s(t.inputPasswordText.errorMsg)+"\n                    ")])]),t._v(" "),e("div",{staticClass:"modal-footer"},[e("button",{staticClass:"btn btn-primary",attrs:{id:"submit"},on:{click:t.submitPassword}},[t._v(t._s(t.inputPasswordText.submit))]),t._v(" "),e("button",{staticClass:"btn btn-default",attrs:{"data-dismiss":"modal"}},[t._v(t._s(t.inputPasswordText.close))])])])])])])},staticRenderFns:[]}},346:function(t,a,e){var s=e(3)(e(249),e(250),!1,function(t){e(247)},"data-v-57089cf2",null);t.exports=s.exports}});