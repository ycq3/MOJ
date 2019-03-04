<template>
  <div id="app">
    <Head></Head>

    <transition name="slide-fade">
      <router-view class="minheight"></router-view>
    </transition>

    <transition name="top-btn">
      <div class="btn go-top " v-on:click="top" v-show="scrollTopData"><span class="glyphicon glyphicon-plane"></span></div>
    </transition>

    <Foot></Foot>
  </div>
</template>

<script>
import Head from "./components/other/_header";
import Foot from "./components/other/_footer";
export default {
    name: 'App',
    components: {Foot,  Head},
    data(){
        return{
            scrollTopData:false,
        }
    },
    methods:{
      top(){//返回页面顶部
          $('body,html').animate({scrollTop:0},1);
      }
    },
    mounted(){
        $(window).scroll(() => {
            if ($(window).scrollTop() > 100)
                this.scrollTopData=true;
            else
                this.scrollTopData=false;
        });
    },
    beforeMount(){

        window.axios.interceptors.response.use(function (response) {
            return response;
        }, (err)=>{
            if (err && err.response) {
                switch (err.response.status) {
                    case 400:
                        err.message = '请求错误(400)';
                        break;
                    case 401:
                        err.message = '未授权，请重新登录(401)';
                        break;
                    case 403:
                        err.message = '拒绝访问';
                        break;
                    case 404:
                        err.message = '请求的地址不存在';
                        break;
                    case 408:
                        err.message = '请求超时(408)';
                        break;
                    case 422:
                        err.message='提交的数据不正确';
                        break;
                    case 429:
                        err.message = '请求过于频繁，系统拒绝响应';
                        break;
                    case 500:
                        err.message = '服务器错误';
                        break;
                    case 501:
                        err.message = '服务未实现(501)';
                        break;
                    case 502:
                        err.message = '网络错误(502)';
                        break;
                    case 503:
                        err.message = '服务不可用(503)';
                        break;
                    case 504:
                        err.message = '网络超时(504)';
                        break;
                    case 505:
                        err.message = 'HTTP版本不受支持(505)';
                        break;
                    default:
                        err.message = `连接出错(${err.response.status})!`;
                }
            } else {
                err.message = '连接服务器失败!'
            }
            this.$toastr.w(err.message);
            return Promise.reject(err);
        });

    }
}
</script>

<style>
  /*//MojCss*/
  @import "../sass/_moj.scss";
  #app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
  }
  .slide-fade-enter-active {
      transition: all .3s ease .6s;
  }
  .slide-fade-leave-active {
      transition: all .5s cubic-bezier(1.0, 0.5, 0.8, 1.0);
  }
  .slide-fade-enter, .slide-fade-leave-to {
      transform: translateX(10px);
      opacity: 0;
  }
  .top-btn-enter-active, .top-btn-leave-active {
    transition: opacity .5s;
    opacity: 0;
  }
  .minheight{
    min-height: 550px;
  }
  .go-top {
    position:fixed;
    top: 90%;
    left: 78%;
    z-index: 999999;
    border-radius: 3em;
    background-color:rgba(33,136,56,0.86);
    color:white;
    width: 40px;
    height: 40px;
  }
</style>
