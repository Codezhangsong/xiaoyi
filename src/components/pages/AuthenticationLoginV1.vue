<template>
  <!--<div class="authentication-wrapper authentication-1 px-4">-->
  <!--<div class="authentication-inner py-5">-->

  <!--&lt;!&ndash; Logo &ndash;&gt;-->
  <!--<div class="d-flex justify-content-center align-items-center">-->
  <!--<div class="ui-w-60">-->
  <!--<div class="w-100 position-relative" style="padding-bottom: 54%">-->
  <!--<svg class="w-100 h-100 position-absolute" viewBox="0 0 148 80" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><linearGradient id="a" x1="46.49" x2="62.46" y1="53.39" y2="48.2" gradientUnits="userSpaceOnUse"><stop stop-opacity=".25" offset="0"></stop><stop stop-opacity=".1" offset=".3"></stop><stop stop-opacity="0" offset=".9"></stop></linearGradient><linearGradient id="e" x1="76.9" x2="92.64" y1="26.38" y2="31.49" xlink:href="#a"></linearGradient><linearGradient id="d" x1="107.12" x2="122.74" y1="53.41" y2="48.33" xlink:href="#a"></linearGradient></defs><path class="fill-primary" transform="translate(-.1)" d="M121.36,0,104.42,45.08,88.71,3.28A5.09,5.09,0,0,0,83.93,0H64.27A5.09,5.09,0,0,0,59.5,3.28L43.79,45.08,26.85,0H.1L29.43,76.74A5.09,5.09,0,0,0,34.19,80H53.39a5.09,5.09,0,0,0,4.77-3.26L74.1,35l16,41.74A5.09,5.09,0,0,0,94.82,80h18.95a5.09,5.09,0,0,0,4.76-3.24L148.1,0Z"></path><path transform="translate(-.1)" d="M52.19,22.73l-8.4,22.35L56.51,78.94a5,5,0,0,0,1.64-2.19l7.34-19.2Z" fill="url(#a)"></path><path transform="translate(-.1)" d="M95.73,22l-7-18.69a5,5,0,0,0-1.64-2.21L74.1,35l8.33,21.79Z" fill="url(#e)"></path><path transform="translate(-.1)" d="M112.73,23l-8.31,22.12,12.66,33.7a5,5,0,0,0,1.45-2l7.3-18.93Z" fill="url(#d)"></path></svg>-->
  <!--</div>-->
  <!--</div>-->
  <!--</div>-->
  <!--&lt;!&ndash; / Logo &ndash;&gt;-->

  <!--&lt;!&ndash; Form &ndash;&gt;-->
  <!--<form class="my-5">-->
  <!--<b-form-group label="Email">-->
  <!--<b-input v-model="credentials.email" />-->
  <!--</b-form-group>-->
  <!--<b-form-group>-->
  <!--<div slot="label" class="d-flex justify-content-between align-items-end">-->
  <!--<div>Password</div>-->
  <!--<a href="javascript:void(0)" class="d-block small">Forgot password?</a>-->
  <!--</div>-->
  <!--<b-input type="password" v-model="credentials.password" />-->
  <!--</b-form-group>-->

  <!--<div class="d-flex justify-content-between align-items-center m-0">-->
  <!--<b-check v-model="credentials.rememberMe" class="m-0">Remember me</b-check>-->
  <!--<b-btn variant="primary">Sign In</b-btn>-->
  <!--</div>-->
  <!--</form>-->
  <!--&lt;!&ndash; / Form &ndash;&gt;-->

  <!--<div class="text-center text-muted">-->
  <!--Don't have an account yet? <a href="javascript:void(0)">Sign Up</a>-->
  <!--</div>-->

  <!--</div>-->
  <!--</div>-->
  <div>
    <div class="loginBG" :style="{width:width+'px',height:height+'px'}">
      <div class="loginBox">
        <div class="loginBoxIndex">
          <img src="../../assets/logo.png">
        </div>
        <div class="loginBoxIndex2">
          <p>昂立考试运营平台</p>
          <div class="input">
            <i class="user"></i>
            <input type="text" placeholder="请输入用户名称" class="user_name" ref="uname">
          </div>
          <div class="input">
            <i class="pwd"></i>
            <input type="password" placeholder="请输入登录密码" class="login_password" ref="password">
            <i class="tabPwd"></i>
          </div>
          <div class="submit" @click="submit()">
            登录
          </div>
          <div class="submit2">
            <span>校翼教育机构运营平台产品v1.0</span><br>
            <span>Copyright © xiaoyi.edutage.com.cn, All Rights Reserved.</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<!-- Page -->
<style src="@/vendor/styles/pages/authentication.scss" lang="scss"></style>
<style scoped>
  @import "AuthenticationLoginV1.css";
</style>
<script>
  export default {
    name: 'pages-authentication-login-v1',
    data() {
      return {
        width: 0,
        height: 0,
        flag: false
      }
    },

    created() {
      this.width = window.innerWidth,
        this.height = window.innerHeight
        console.log(1111)
      // if(sessionStorage.getItem('watchStorage')){
      //   const link = "/home/home"
      //   this.$router.push({path:link});
      // }else{
      //   const link = "/pages/authentication/login-v1"
      //   this.$router.push({path:link});
      // }

    },


    methods: {
       
     submit() {
        
        console.log(3333);
          var that=this
           var uname = this.$refs.uname.value
           var password = this.$md5(this.$refs.password.value)

           

            that.axios.post(`${this.devUrl}org/login`, {
               uname: uname, password: password
               })
            .then(function (response) {
              console.log(response.data)
             
          var data = response.data
          if (data.code === 200) {
            //sessionStorage.setItem('watchStorage',1)
            that.sendVal = true
            that.msgContent = '登录成功'
            console.log(data.data.id);
            
           that.$utils.setCookie('org_code',data.data.id);
            that.$utils.setCookie('username',data.data.adminname);
            const link = "/members/list/"
            window.open(link, '_self')
          }else{
               const link = "/pages/authentication/login-v1"
              that.$router.push({path:link});
          }
        })
      
     }
    },
  }
</script>
