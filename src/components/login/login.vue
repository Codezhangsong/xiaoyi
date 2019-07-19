<template>
  <div class="login_body">
    <div class="loginBG" :style="{width:width+'px',height:height+'px'}">
      <div class="loginBox">
        <div class="loginBoxIndex">
          <img src="../../assets/logo.png">
        </div>
        <div class="loginBoxIndex2">
          <p>运营平台</p>
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
  @import "login.css";
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
      //   const link = "/login/login"
      //   this.$router.push({path:link});
      // }

    },


    methods: {

     submit() {

           var that=this
           var uname = this.$refs.uname.value
           var password = this.$md5(this.$refs.password.value)

            that.axios.post(`${this.devUrl}adminaccount/check`, {
               uname: uname, password: password
               })
            .then(function (response) {
             // console.log(response.data.code)

          var data = response.data
           console.log(response.data.code)
          if (data.code === 200) {
            //sessionStorage.setItem('watchStorage',1)
            that.sendVal = true
            that.msgContent = '登录成功'
//console.log(data.data.id);
              console.log(data.data.id);
               console.log(data.data.adminname);

            
            that.$utils.setCookie('username',data.data.adminname);
            const link = "/home/home/"
            window.open(link, '_self')
          }else{
               const link = "/login/login"
              that.$router.push({path:link});
          }
        })
                .then(function (response) {
                  // console.log(response.data.code)

                  var data = response.data
                  console.log(response.data.code)
                  if (data.code === 200) {
                    //sessionStorage.setItem('watchStorage',1)
                    that.sendVal = true
                    that.msgContent = '登录成功'
//console.log(data.data.id);
                    console.log(data.data.id);
                    console.log(data.data.adminname);

                    that.$utils.setCookie('org_code',data.data.id);
                    that.$utils.setCookie('username',data.data.adminname);
                    const link = "/home/home/"
                    window.open(link, '_self')
                  }else{
                    const link = "/login/login"
                    that.$router.push({path:link});
                  }
                })

      }
    },
  }
</script>
