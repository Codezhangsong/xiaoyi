<template>
  <b-navbar toggleable="lg" :variant="getLayoutNavbarBg()" class="layout-navbar align-items-lg-center container-p-x">

    <!-- Brand demo (see demo.css) -->
    <b-navbar-brand to="/" class="app-brand demo d-lg-none py-0 mr-4">
      <span class="app-brand-logo demo ">
       <img src="../assets/navlogo.png" style="height: 30px;width: 30px">
      </span>
      <span class="app-brand-text demo font-weight-normal ml-2">运营平台</span>
    </b-navbar-brand>

    <!-- Sidenav toggle (see demo.css) -->
    <b-navbar-nav class="layout-sidenav-toggle d-lg-none align-items-lg-center mr-auto" v-if="sidenavToggle">
      <a class="nav-item nav-link px-0 mr-lg-4" href="javascript:void(0)" @click="toggleSidenav">
        <i class="ion ion-md-menu text-large align-middle" />
      </a>
    </b-navbar-nav>

    <b-navbar-toggle target="app-layout-navbar"></b-navbar-toggle>

    <b-collapse is-nav id="app-layout-navbar">
      <!-- Divider -->
      <hr class="d-lg-none w-100 my-2">

      <b-navbar-nav class="align-items-lg-center">
        <!-- Search -->
        <label class="nav-item navbar-text navbar-search-box p-0 active">
          <img src="../assets/top_search.png" height="14px" width="14px">
          <span class="navbar-search-input pl-2 z_xgp">
            <input type="text" class="form-control navbar-text mx-2" placeholder="请输入搜索内容" style="width:200px;color: #806B78DE">
          </span>
        </label>
      </b-navbar-nav>

      <b-navbar-nav class="align-items-lg-center ml-auto">
        <b-nav-item-dropdown :right="!isRTL" class="demo-navbar-user z_dn">
          <template slot="button-content">
            <span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
             <!-- <img :src="`${baseUrl}img/avatars/1.png`" alt class="d-block ui-w-30 rounded-circle">-->
               <img v-if="orgInfo.pic_url" :src="orgInfo.pic_url" alt class="d-block ui-w-30 rounded-circle">
              <img v-if="!orgInfo.pic_url"  src="../assets/userh.png" alt class="d-block ui-w-30 rounded-circle">
              <span class="px-1 mr-lg-2 ml-2 ml-lg-0">你好，{{orgInfo.org_name?orgInfo.org_name:'姓名'}}</span>
            </span>
          </template>
          <b-dd-item class="z_top_sj"></b-dd-item>
          <b-dd-item><img src="../assets/z_top_password.png" width="15" height="17">修改密码</b-dd-item>
         
          <b-dd-item><img src="../assets/z_top_infor.png" width="15" height="17">基本信息</b-dd-item>
        </b-nav-item-dropdown>
        <!-- Divider -->
        <div class="nav-item d-none d-lg-block text-big font-weight-light line-height-1 opacity-25 mr-3 ml-1 z_top_line">|</div>

        <b-nav-item-dropdown no-caret :right="!isRTL" class="demo-navbar-notifications mr-lg-2">
          <template slot="button-content">
            <i class="ion ion-md-notifications-outline navbar-icon align-middle"></i>
          <!--  <span class="badge badge-primary badge-dot indicator"></span>
            <span class="d-lg-none align-middle">&nbsp; Notifications</span>-->
          </template>

          <b-dd-item class="z_top_sj"></b-dd-item>
          <!--<b-dd-item @click="showDiv2"  class="z_btms">消息中心 <img src="../assets/top_set.png" width="17" height="17"></b-dd-item>-->
          <b-dd-item @click="allMsg" class="z_btms">全部消息 <span class="z_tips_num">{{usersData.unread?usersData.unread:0}}</span></b-dd-item>
          <b-dd-item class="z_btms" v-if="messageType.length" v-for="(item,index) in messageType">{{item.type_name}} <span class="z_tips_num">{{item.unread}}</span> </b-dd-item>
        </b-nav-item-dropdown>

        <img src="../assets/z_top_btn.png" width="20" height="20" @click="showDiv">
      </b-navbar-nav>
    </b-collapse>

  </b-navbar>

</template>

<script>
export default {
  name: 'app-layout-navbar',

  props: {
    sidenavToggle: {
      type: Boolean,
      default: true
    }
  },
  data () {
    return {
      isShow: false,
      isShow2: false,
      usersData: {},
      orgInfo: [],
      messageType: []
    }
  },
  mounted () {
    this.getData()
  },

  methods: {
    toggleSidenav () {
      this.layoutHelpers.toggleCollapsed()
    },

    getLayoutNavbarBg () {
      return this.layoutNavbarBg
    },
    showDiv: function () {
      this.$store.commit('setShow', !this.$store.state.show)
    },

    showDiv2: function () {
      this.$store.commit('setShow2', !this.$store.state.show2)
    },
    allMsg: function () {
      window.open('/messages/list', '_self')
    },
    getData: function () {
      var that = this
      // that.axios.get(`${this.baseUrl}json/table-data.json`, that.getParamsToken({}))
      that.axios.post(`${this.devUrl}org/info`, that.getParamsToken({
        org_code: ''
      }))
        .then(function (response) {
          var data = response.data
          if (data.code === 200) {
            var datas = data.data
            that.usersData = datas
            that.orgInfo = datas.org_info ? datas.org_info : []
            that.messageType = datas.messageType ? datas.messageType : []
          }
        })
    },
    getParamsToken (paramsMd5) {
      var paramsEndData = {}
      var paramsMulArr = []
      var timeStamp = Math.round(new Date().getTime() / 1000)
      paramsMd5['timeStamp'] = timeStamp
      for (let i in paramsMd5) {
        var o = { key: i, val: paramsMd5[i] }
        paramsMulArr.push(o)
      }
      paramsMulArr.sort()
      var str = '' // app_key: 'edutage520'
      paramsMulArr.forEach((item, index, arr) => {
        str += index + '' + item['val']
        paramsEndData[item['key']] = item['val']
      })
      str += `${this.appTokenKey}`
      var token = this.$md5(str)
      paramsEndData['token'] = token
      return paramsEndData
    }
  }
}
</script>
<style>
  .z_dn .dropdown-menu-right{
    display: none!important;
  }

  @media (min-width: 992px){
    .dropdown-menu {
      margin-top: 20px!important;

    }
    .demo-navbar-notifications .dropdown-menu{
      width: 160px;
      margin-top: 25px!important;
    }

  }
  .bg-navbar-theme{
    box-shadow:0px 5px 20px 0px rgba(107,120,222,0.5);
    opacity:0.9;
  }
  .layout-2 .layout-sidenav{
    z-index: 9;
  }

  .z_top_sj{
    width: 15px;
    height: 15px;
    position: absolute;
    top: -7.5px;
    right: 15px;
    transform: rotate(45deg);
    background: #fff;
    z-index: 1001;
    padding: 0;
  }
  .dropdown-toggle::after{
    display: none;
  }

  .z_top_line{
    display: inline-block;
    width:1px;
    height:20px;
    background:#E9E9E9;
  }

  .badge-primary {
    background-color: #FF5E38;
    color: #fff;
  }

  .demo-navbar-notifications .dropdown-menu{
    overflow:inherit;

  }
  .z_btms{
    border-bottom: 1px solid #F4F4F4!important;
  }

  .dropdown-menu-right .dropdown-item img{
    float: right;
  }
  .dropdown-menu-right .dropdown-item span.z_tips_num{
    display: inline-block;
    float: right;
    width:16px;
    height:16px;
    line-height: 16px;
    background:rgba(255,109,74,1);
    border-radius:50%;
    color: #ffffff;
    font-size: 10px;
    text-align: center;

  }

  /*弹窗*/
  .z_tcbox{
    width: 100%;
    height:100vh;
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.3);
    z-index: 100;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;

  }
  .z_tcbox .z_tcbox_div{
    background: #ffffff;
    border-radius:10px;
  }
  .z_tcbox .z_tcbox_top{
    width:100%;
    height:70px;
    background:#F0F0F0;
    border-radius:10px;
    position: relative;
  }
  .z_tcbox .z_tcbox_top .z_tcbox_top_tips{
    position: absolute;
    top: -6px;
    left: 40px;
    width: 70px;
    height: 100px;
    background:url("../assets/z_tc_tipbg.png") top center no-repeat;
    background-size: 100% 100%;
    font-size: 20px;
    text-align: center;
    font-size:20px;
    font-family:SourceHanSansCN-Regular;
    font-weight:400;
    color:#fff;
    padding: 17px 15px 34px 15px;
  }
  .z_tcbox_top p{
    position: absolute;
    right:43px;
    top: 5px;
    display: block;
    font-size: 40px;
    width: 20px;
    height: 20px;
    color: #9d9ea0;
  }
  .z_tcbox .z_tcbox_centent{
    width: 100%;
  }

  .z_set_messg_cent{
    width: 760px;
    height: 480px;
  }

  .z_set_messg_cent .z_tcbox_centent{
    padding: 40px;
  }
  .z_set_messg_cent h2{
    font-size:16px;
    font-weight:600;
    color:#333333;
  }
  .z_tcbox_centent form{
    font-size: 16px;
    color: #666666;
    font-weight:400;
    margin: -15px auto 30px auto;
  }

  .checkbox {
    padding-left: 20px;
    display: inline-block;
  }

  .radio, .checkbox {
    position: relative;
    display: block;
    margin-top: 10px;
    margin-bottom: 10px;
  }
  .checkbox input[type=checkbox], .checkbox input[type=radio] {
    opacity: 0;
    z-index: 1;
  }
  input[type="radio"], .radio-inline input[type="radio"], .checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"] {
    position: absolute;
    margin-top: 4px \9;
    margin-left: -20px;
  }
  input[type="radio"], input[type="checkbox"] {
    margin: 1px 0 0;
    line-height: normal;
    width: 22px;
    height: 22px;
  }
  .checkbox label {
    display: inline-block;
    vertical-align: middle;
    position: relative;
    padding-left: 5px;
  }
  .radio label, .checkbox label {
    min-height: 20px;
    padding-left: 20px;
    margin-bottom: 0;
    font-weight: normal;
    cursor: pointer;
  }
  .checkbox label::before {
    content: "";
    display: inline-block;
    position: absolute;
    width: 22px;
    height: 22px;
    left: 0;
    margin-left: -20px;
    border: 1px solid #6B78DE;
    border-radius: 3px;
    background-color: #fff;
    -webkit-transition: border .15s ease-in-out,color .15s ease-in-out;
    -o-transition: border .15s ease-in-out,color .15s ease-in-out;
    transition: border .15s ease-in-out,color .15s ease-in-out;
  }
  input[type=checkbox].styled:checked+label:after, input[type=radio].styled:checked+label:after {
    font-family: fontawesome;
    content: "\f00c";
  }
  .checkbox-primary input[type=checkbox]:checked+label::after, .checkbox-primary input[type=radio]:checked+label::after {
    color: #fff;
  }
  .checkbox input[type=checkbox]:checked+label::after, .checkbox input[type=radio]:checked+label::after {
    font-family: fontawesome;
    content: "\f00c";
  }
  .checkbox label::after {
    display: inline-block;
    position: absolute;
    width: 22px;
    height: 22px;
    left: 0;
    top: 0;
    margin-left: -20px;
    padding-left: 5px;
    padding-top: 3px;
    font-size: 12px;
    color: #555;
  }

  .checkbox-primary input[type=checkbox]:checked+label::before,.checkbox-primary input[type=radio]:checked+label::before{background-color:#6B78DE;border-color:#6B78DE}
  .checkbox-primary input[type=checkbox]:checked+label::after,.checkbox-primary input[type=radio]:checked+label::after{color:#fff}
  .z_btn_submit{
    width: 62%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin:10px auto auto auto !important;
  }
  .z_btn_submit button{
    width: 200px;
    height: 50px;
    text-align: center;
    line-height: 50px;
    color: #999999;
    font-size: 18px;
    background: #EBEEF3;
    border: none;
    border-radius: 4px;

  }
  .z_btn_submit button.z_messg_submit{
    color: #fff;
    background: #6B78DE;
  }

  .z_login_cent{
    width: 500px;
    height: 330px;
  }

  .z_login_cent .z_btn_submit{
    width: 84%;
  }
  .z_tcbox_centent p{
    font-size: 18px;
    color: #555555;
    text-align: center;
    display: block;
    margin-bottom: 70px;
    margin-top: 40px;
  }

  .z_xgp input::placeholder{
    color: #ffffff!important;
    font-family:SourceHanSansCN-Light;
    font-weight:300;
    line-height:31px;
    opacity:0.3;
    font-size: 14px;
  }
  html:not([dir=rtl]) .ml-1, html:not([dir=rtl]) .mx-1{
    margin-left: 1rem!important;
  }
  .ion{
    margin-right: 10px!important;
  }
</style>
