<template>
    <div>
        <h5 class="mb-4 row z_flex">
            <div class="col-sm-11 col-xl-11 z_toptitl col-md-10">
                <i class="fas fa-map-marker-alt mr"></i> 应用管理 <i class="ion ion-ios-arrow-forward mr ml"></i>
                <span class="c6B78DE">应用列表</span>
            </div>
            <reback></reback>
        </h5>
        <div class="row">
            <div class="col">
                <b-card class="z_yydiv">
                    <div class="z_yybox col-lg-12">
                        <div class="z_yybox_title col-sm-12 row clearPaddingMargin">
                            <div class="col-lg-1 clearPaddingMargin z_new_ys">
                                <img src="../../assets/z_yyimg1.png" width="91" height="90">
                            </div>

                            <div class="col-sm-12 col-lg-11 clearPaddingMargin">
                                <div class="z_yybox_tit">
                                    <h2>添翼申学</h2>
                                    <div class="z_yybox_pf">
                                        <p>应用评分:
                                            <img src="../../assets/z_yyx1.png">
                                            <img src="../../assets/z_yyx1.png">
                                            <img src="../../assets/z_yyx1.png">
                                            <img src="../../assets/z_yyx2.png">
                                            <span>3.9分</span>
                                        </p>
                                        <p><img src="../../assets/z_yyimg2.png">2000次</p>
                                    </div>
                                </div>
                                <button class="z_yybox_btn z_btna z_btnas"><!--<i class="lnr lnr-download"></i>-->已安装</button>
                            </div>

                        </div>
                        <div class="row col-sm-12 col-lg-12 clearPaddingMargin z_yybox_cent">
                            <img src="../../assets/tybg.png">
                            <h2>应用介绍</h2>
                            <p>中国电信·添翼申学作为教育一站式学习平台，是中国电信上海公司重力打造的教育品牌。该平台覆盖从幼儿到高中的全学龄学生，综合沪上多家机构及名师资源，汇集语文、
                                数学、英语、STEAM等全科内容。平台采用直录播等课程形式，融合PC、IPTV、手机三屏学习场景。</p>
                            <p>添翼申学教育品牌已与昂立、学而思、四季等多家教育名牌机构达成战略合作，共同探索智慧教育全新应用场景，打造K12王牌科技教育产品。</p>
                        </div>
                    </div>
                    <div class="z_yybox col-lg-12">
                        <div class="z_yybox_title col-sm-12 row clearPaddingMargin ">
                            <div class="col-lg-1 clearPaddingMargin z_new_ys">
                                <img src="../../assets/z_yyimgwx.png" width="91" height="90">
                            </div>

                            <div class="col-sm-12 col-lg-11 clearPaddingMargin">
                                <div class="z_yybox_tit">
                                    <h2>微信编辑器</h2>
                                    <div class="z_yybox_pf">
                                        <p>应用评分:
                                            <img src="../../assets/z_yyx1.png">
                                            <img src="../../assets/z_yyx1.png">
                                            <img src="../../assets/z_yyx1.png">
                                            <img src="../../assets/z_yyx2.png">
                                            <span>3.9分</span>
                                        </p>
                                        <p><img src="../../assets/z_yyimg2.png">2000次</p>
                                    </div>
                                </div>

                                <button class="z_yybox_btn z_btnas"   @click="Dj()" v-if="!flag">
                                    <i class="lnr lnr-download"></i>
                                    <!--{{this.msgContent}}-->
                                    安装
                                </button>

                                <button class="z_yybox_btn z_btna z_btnas" v-if="flag" @click="Djtj2()">已安装</button>
                            </div>

                        </div>
                        <div class="row col-sm-12 col-lg-12 clearPaddingMargin z_yybox_cent">
                            <img src="../../assets/bjq.png">
                            <h2>应用介绍</h2>
                            <p>专业致力于微信文章排版美化，提供各种微信图文素材编辑模板，大幅度提供微信图文内容的编辑效率，让用户在微信图文 、文章、内容排版、文本编辑、素材编辑上更加方便。</p>
                        </div>
                    </div>
                </b-card>
            </div>
        </div>
        <!--引入提示弹框-->
        <popup v-model="sendVal" :content="msgContent"></popup>

        <div class="z_tcbox z_login_out" v-if="djtc">
            <div class="row z_login_cent z_tcbox_div">
                <div class="z_tcbox_top">
                    <div class="z_tcbox_top_tips">
                        温馨<br>提示
                    </div>
                    <p @click="Dj()"><img src="../../assets/closetc.png" width="20" height="20" v-show="flag"></p>
                </div>
                <div class="z_tcbox_centent">
                    <form role="form">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 z_cx_box_lable mb-3">
                                <div class="z_tcxgbox">
                                    <label>openId</label>
                                </div>
                                <input class="z_cx_input z_dr z_cx_input_id" placeholder="openId" type="text" ref="openId">
                            </div>
                            <div class="col-md-12 col-sm-12 z_cx_box_lable">
                                <div class="z_tcxgbox">
                                    <label>secret</label>
                                </div>
                                <input class="z_cx_input z_dr z_cx_input_id" placeholder="secret" type="text" ref="secret">
                            </div>
                        </div>
                    </form>

                    <div class="z_btn_submit">
                        <button class="z_messg_submit" @click="Djtj()">提交</button>
                        <button @click="Dj()">取消</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    @import "list.css";
    .z_tcxgbox{
        display: inline-block;
        width: 92px;
        text-align: right;
    }
     .z_cx_input{
        width:70%;
         display: inline-block;
        height:40px;
        background:rgba(246,247,251,1);
        border:1px solid rgba(232,235,245,1);
        border-radius:4px;
        margin-left: 17px;

    }
    .z_cx_input_id{
        text-indent: 1rem;
    }
    .z_btn_submit {

        margin: 10px auto auto 40px !important;
    }

</style>

<script>
    import reback from '../popup/reback';
    import popup from '../popup/popup'
  export default {
    name: '',
    metaInfo: {
      title: ''
    },
    components: {
        popup,reback
    },
    data: () => ({
        djtc:false,
        flag:false,
        sendVal: false,
        msgContent: ''
    }),

    computed: {

    },
  methods: {

    Dj(){
     alert('即将开发，敬请期待!');
      this.djtc = !this.djtc
      /*this.resetSetItem('watchStorage', this.flag)*/
    },
    Djtj(){
      var that = this
      var openId = that.$refs.openId.value
      var secret = that.$refs.secret.value
      var params = {
        openId: openId,
        secret: secret
      }
      that.axios.post(`${this.devUrl}org/install`, that.getParamsToken(params))
        .then(function (response) {
          var data = response.data
          if (data.code === 200) {
            that.flag = !that.flag
            that.resetSetItem('watchStorage', that.flag)
            if(sessionStorage.getItem('watchStorage')){
              /*that.msgContent = '已安装'*/
              /*  alert(sessionStorage.getItem('watchStorage'))*/
              that.flag = true
              location.reload()
            }
          } else {
            that.flag = false
          }
        })
    },

    Djtj2(){
      this.sendVal = true
      this.msgContent = '您已经安装'
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
    },

    },
    created () {
      if(sessionStorage.getItem('watchStorage')){
       // this.msgContent = '已安装'
        this.flag = true
        /* alert(sessionStorage.getItem('watchStorage'))*/
      }else{
        //this.msgContent = '安装'
        this.flag = false
      }
    }
  }
</script>
