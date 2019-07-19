<script src="../../../orgNew/src/globals.js"></script>
<script src="../router/index.js"></script>
<script src="../router/link.js"></script>
<script src="../router/parents.js"></script>
<template>
  <sidenav :orientation="orientation" :class="curClasses">

    <!-- Brand demo (see src/demo.css) -->
    <div class="app-brand demo" v-if="orientation !== 'horizontal'">
       <span class="app-brand-logo demo ">
       <img src="../assets/navlogo.png" style="height: 30px;width: 30px">
      </span>
      <router-link to="/" class="app-brand-text demo sidenav-text font-weight-normal ml-2">运营平台</router-link>
      <a href="javascript:void(0)" class="layout-sidenav-toggle sidenav-link text-large ml-auto" @click="toggleSidenav()">
        <i class="ion ion-md-menu align-middle"></i>
      </a>
    </div>
    <div class="sidenav-divider mt-0" v-if="orientation !== 'horizontal'"></div>    <!--分割线-->

    <!-- Links -->
    <div class="sidenav-inner" :class="{ 'py-1': orientation !== 'horizontal' }">

      <!-- 首页 -->
      <sidenav-router-link icon="ion ion-md-home" to="/home/home" :exact="true">后台首页</sidenav-router-link>
      <div class="sidenav-divider mt-0" v-if="orientation !== 'horizontal'"></div>    <!--分割线-->
      <sidenav-header class="small font-weight-semibold">用户中心</sidenav-header>




      <!-- 家长管理 -->
      <!--<sidenav-header class="small font-weight-semibold">分类</sidenav-header>-->
      <sidenav-menu icon="fas fa-user-tie" :active="isMenuActive('/parents')" :open="isMenuOpen('/parents')">
        <template slot="link-text">家长管理</template>
        <!--<sidenav-router-link to="/parents/edit" :exact="true"><span class="dd">·</span>家长详情/编辑</sidenav-router-link>-->
        <sidenav-router-link to="/parents/list" :exact="true"><span class="dd">·</span>家长列表</sidenav-router-link>
        <sidenav-router-link to="/parents/tag_list" :exact="true"><span class="dd">·</span>家长标签</sidenav-router-link>
        <sidenav-router-link to="/parents/level_list" :exact="true"><span class="dd">·</span>家长等级</sidenav-router-link>
      </sidenav-menu>

      <!-- 学生管理 -->
      <!--<sidenav-menu icon="ion ion-md-person" :active="isMenuActive('/students')" :open="isMenuOpen('/students')">-->
      <sidenav-menu icon="fas fa-user-graduate" :active="isMenuActive('/students')" :open="isMenuOpen('/students')">
        <template slot="link-text">学生管理</template>
        <!--<sidenav-router-link to="/students/edit" :exact="true"><span class="dd">·</span>学生详情/编辑</sidenav-router-link>-->
        <sidenav-router-link to="/students/list" :exact="true"><span class="dd">·</span>学生列表</sidenav-router-link>
      </sidenav-menu>


      <!-- 渠道管理-->
   <!--   <sidenav-menu icon="ion ion-md-person" :active="isMenuActive('/mechanism')" :open="isMenuOpen('/mechanism')">
        <template slot="link-text">渠道管理</template>
        <sidenav-router-link to="/mechanism/list" :exact="true"><span class="dd">·</span>渠道管理</sidenav-router-link>
      </sidenav-menu>-->
  <!--

      <!-- 机构管理-->
     <sidenav-menu icon="ion ion-md-person" :active="isMenuActive('/org')" :open="isMenuOpen('/org')">
        <template slot="link-text">机构管理</template>
        <sidenav-router-link to="/org/list" :exact="true"><span class="dd">·</span>机构管理</sidenav-router-link>
      </sidenav-menu>


      <sidenav-menu icon="fas fa-sitemap" :active="isMenuActive('/mechanism')" :open="isMenuOpen('/mechanism')">
        <template slot="link-text">渠道管理</template>
        <sidenav-router-link to="/mechanism/list" :exact="true"><span class="dd">·</span>渠道管理</sidenav-router-link>
      </sidenav-menu>
      



      <div class="sidenav-divider mt-0" v-if="orientation !== 'horizontal'"></div>    <!--分割线-->
      <sidenav-header class="small font-weight-semibold">数据中心</sidenav-header>

      <!-- 数据统计 -->


    <!--
      <sidenav-menu :active="isMenuActive('/system')" :open="isMenuOpen('/system')">
        <template slot="link-text"><img src="../assets/navxtgl.png"  class="z_nav_img">系统管理</template>
        <sidenav-router-link to="/system/list" :exact="true"><span class="dd">·</span>应用列表 </sidenav-router-link>
      </sidenav-menu>

  -->


      <sidenav-menu icon="ion ion-md-pie" :active="isMenuActive('/dashboards')" :open="isMenuOpen('/dashboards')">
        <template slot="link-text">数据统计</template>
        <sidenav-router-link to="/dashboards/dashboard-1" :exact="true"><span class="dd">·</span>营销活动 </sidenav-router-link>
        <sidenav-router-link to="/dashboards/dashboard-2" :exact="true"><span class="dd">·</span>家长数据统计 </sidenav-router-link>
        <sidenav-router-link to="/dashboards/dashboard-3" :exact="true"><span class="dd">·</span>课程数据统计</sidenav-router-link>
        <sidenav-router-link to="/dashboards/dashboard-4" :exact="true"><span class="dd">·</span>学生数据统计</sidenav-router-link>
      </sidenav-menu>

      <!-- 消息管理 -->
      <sidenav-menu icon="ion ion-ios-chatboxes" :active="isMenuActive('/messages')" :open="isMenuOpen('/messages')">
        <template slot="link-text">消息管理</template>
        <!--
        <sidenav-router-link to="/messages/type_list" :exact="true"><span class="dd">·</span>消息分类</sidenav-router-link>
        -->
        <sidenav-router-link to="/messages/list" :exact="true"><span class="dd">·</span>消息列表</sidenav-router-link>
      </sidenav-menu>

      <div class="sidenav-divider mt-0" v-if="orientation !== 'horizontal'"></div>    <!--分割线-->
      <sidenav-header class="small font-weight-semibold">应用中心</sidenav-header>


       <sidenav-menu icon='ion ion-ios-apps ' :active="isMenuActive('/yygl')" :open="isMenuOpen('/yygl')">
        <template slot="link-text">应用管理</template>
        <sidenav-router-link to="/yygl/list" :exact="true"><span class="dd">·</span>应用列表 </sidenav-router-link>
      </sidenav-menu>

      <!-- 添翼申学 -->
      <sidenav-menu   icon="fas fa-child" :active="isMenuActive('/zb')" :open="isMenuOpen('/zb')">
        <template slot="link-text">添翼申学</template>
        <sidenav-router-link to="/zb/list" :exact="true"><span class="dd">·</span>直播课程列表</sidenav-router-link>
       <!-- <sidenav-router-link to="/zb/edit" :exact="true"><span class="dd">·</span>直播课程详情</sidenav-router-link>-->
      </sidenav-menu>
      <!--微信图文-->
      <!--
      <sidenav-menu icon="fas fa-comments" v-show="data" :active="isMenuActive('/wxarticles')" :open="isMenuOpen('/wxarticles')">
        <template slot="link-text">微信图文</template>
        <sidenav-router-link to="/wxarticles/list" :exact="true"><span class="dd">·</span>营销文章列表</sidenav-router-link>
        <sidenav-router-link to="/wxarticles/edit" :exact="true"><span class="dd">·</span>营销文章编辑页</sidenav-router-link>
      </sidenav-menu>
      -->
    <!--  <sidenav-menu :active="isMenuActive('/members')" :open="isMenuOpen('/members')">
        <template slot="link-text"><img src="../assets/exam_icon.png"  class="z_nav_img">考试系统</template>
        <sidenav-menu :active="isMenuActive('/members')" :open="isMenuOpen('/members')">
          <template slot="link-text"><span class="dd">·</span>会员管理</template>
          <sidenav-router-link to="/members/list" :exact="true"><span class="dd">·</span>会员列表</sidenav-router-link>
          <sidenav-router-link to="/members/cash" :exact="true"><span class="dd">·</span>会员提现</sidenav-router-link>

        </sidenav-menu>
      </sidenav-menu>-->
    </div>
  </sidenav>
</template>
<script>
  import { Sidenav, SidenavLink, SidenavRouterLink, SidenavMenu, SidenavHeader, SidenavBlock, SidenavDivider } from '@/vendor/libs/sidenav'

  export default {
    name: 'app-layout-sidenav',
    components: {
      Sidenav,
      SidenavLink,
      SidenavRouterLink,
      SidenavMenu,
      SidenavHeader,
      SidenavBlock,
      SidenavDivider
    },

    props: {
      orientation: {
        type: String,
        default: 'vertical'
      }
    },
    data(){
      return{
        data:false
      }
    },

    created () {


      if(sessionStorage.getItem('watchStorage')){
        this.data = sessionStorage.getItem('watchStorage')
      /*  alert(sessionStorage.getItem('watchStorage'))*/
      }else{
        this.data = false
      }


    },


    computed: {

      curClasses () {
        let bg = this.layoutSidenavBg

        if (this.orientation === 'horizontal' && (bg.indexOf(' sidenav-dark') !== -1 || bg.indexOf(' sidenav-light') !== -1)) {
          bg = bg
                  .replace(' sidenav-dark', '')
                  .replace(' sidenav-light', '')
                  .replace('-dark', '')
        }

        return `bg-${bg} ` + (
                this.orientation !== 'horizontal'
                        ? 'layout-sidenav'
                        : 'layout-sidenav-horizontal container-p-x flex-grow-0'
        )
      }
    },

    methods: {
      isMenuActive (url) {
        return this.$route.path.indexOf(url) === 0
      },

      isMenuOpen (url) {
        return this.$route.path.indexOf(url) === 0 && this.orientation !== 'horizontal'
      },

      toggleSidenav () {
        this.layoutHelpers.toggleCollapsed()
      }
    }
  }
</script>
<style>
  .fa-child:before{
    font-size: 18px;
  }
  .fa-sitemap:before{
    font-size: 14px;
  }

  .ion{
    margin-right: 0.25rem!important;
  }
  .z_nav_img{
    width: 16px;
    height: 16px;
    margin-right: 10px;
  }

  .dd{
    display: block;
    float: left;
    line-height: 23px;
    font-size: 36px;
  }
  .sidenav-vertical .sidenav-menu .sidenav-link {
    padding-left: 3.4rem !important;
  }
  .sidenav-vertical .sidenav-icon{
    width: 1.3rem;
  }
  .fa-map-marker-alt{
    margin-right: 10px;
  }
  .c6B78DE{
    color: #6B78DE;
  }

  /*返回手势*/
  .z_toptitr span{
    display: block;
    cursor: pointer;
      color: #666666;
  }
  .z_toptitr span img{
    margin-right: 8px;
    margin-top: -3px;
  }


</style>
