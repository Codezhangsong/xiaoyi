<template>
    <div>

        <h5 class="mb-4 row z_flex">
            <div class="col-sm-11 col-xl-11 z_toptitl">
                <i class="fas fa-map-marker-alt mr"></i> 添翼申学 <i class="ion ion-ios-arrow-forward mr ml"></i>
                <span class="c6B78DE">直播课列表</span>
            </div>
            <reback></reback>
        </h5>


        <b-card no-body class="mb-4">
            <div class="row z_zb_top clearPaddingMargin z_zbtop_new z_zbtop_newbox">
                <div class="z_zb_top_tips col-xl-4  clearPaddingMargin">为您找到相关结果约{{userDataCount}}个，搜索用时（0.23秒）</div>
                <div class="z_zb_top_search row col-xl-8  clearPaddingMargin z_zbtop_new">
                    <div class="input-group  input-group1 col-xl-7 mb-4">
                        <input type="text" class="form-control" placeholder="请输入关键词" ref="searchCon" style="height: 20px;">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" @click.prevent="search">
                                搜索
                            </button>
                        </span>
                    </div>
                     <div class="col-xl-2 z_zb_top_option mb-4">
                        <b-select @change.native="setSort1($event)">
                           <option selected="selected">请选择审核状态</option>
                            <option value="1">未审核</option>
                            <option value="5">审核通过</option>
                            <option value="3">审核不通过</option>
                        </b-select>
                    </div>
                    <!-- <div class="col-xl-2 z_zb_top_option mb-4">
                        <b-select v-model="filterRole" @change.native="setSort($event)">
                            <option value="asc">时间正序</option>
                            <option value="desc">时间倒序</option>
                        </b-select>
                    </div> -->

                </div>
            </div>


            <b-list-group :flush="true">
                <li class="list-group-item py-4"  v-for="(item,index) in usersData">
                    <div class="media flex-wrap">
                        <div class="d-none d-sm-block ui-w-140 z_lg_wd" @click="toEdit(item.id)">

                            <a class="d-block ui-bg-cover"><img :src="item.cover_img"></a>
                        </div>

                        <div class="media-body ml-sm-4 z_zb_cent">
                            <h3 class="">
                              {{item.class_type}}：{{item.course_name}}
                                <span class="z_zb_cent_tile">￥{{item.price}}</span>
                            </h3>

                            <div class="d-flex flex-wrap align-items-center mb-2 z_zb_top_time">
                                <p class="p">{{item.class_tag?item.class_tag:'语文'}}</p>
                                <p class="p"><img src="../../assets/z_zb_img4.png" width="18" height="16">课时数：{{item.course_num}} </p>
                                <p class="p">发布时间：{{item.reg_date}}</p>
                            </div>


                            <div class="z_zb_nr">{{item.course_feature}}</div>
                            <div class="mt-2 z_zb_sj">
                                <p class="p">UV:<span class="z_zb_sj_num">{{item.UV}}</span></p>
                                <p class="p">PV：<span class="z_zb_sj_num">{{item.PV}}</span> </p>
                                <p class="p">课程编号<span class="z_zb_sj_num">{{item.id}}</span></p>
                            </div>
                        </div>
                      <!--  <div class="z_zb_cent_boxr ui-w-100">
                            <img v-if="item.status==2|| item.status==3" src="../../assets/z_zb_img3.jpg" >
                            <img v-if="item.status==4" src="../../assets/z_zb_img1.jpg" >
                            <img v-if="item.status==5 || item.status==1" src="../../assets/z_zb_img2.jpg" >
                        </div>-->
                        <div class="z_zb_cent_boxr ui-w-100">
                            <!--<img v-if="item.status==5 || item.status==1" src="../../assets/z_zb_img2.jpg" >-->
                            <!-- <img src="../../assets/z_zb_img2.png"> -->
                             <img v-if="item.status==2|| item.status==3" src="../../assets/z_zb_img3.jpg" >
                            <img v-if="item.status==5"  src="../../assets/z_zb_img1.jpg" >
                            <img v-if="item.status==1" src="../../assets/z_zb_img2.jpg" >
                           
                            <div  v-if="item.status==1" class="sh_btn" @click="shtc(item.id,5)"><img src="../../assets/sh.png">审核通过</div>
                             <div  v-if="item.status==1" class="sh_btn" @click="shtc(item.id,3)"><img src="../../assets/sh.png">审核不通过</div>
                        </div>
                    </div>
                </li>
            </b-list-group>
        </b-card>

        <div class="row col-sm-12 col-lg-12 clearPaddingMargin" >
            <ul class="pagenation" v-if="totalItems">
                <li class="page_first" v-if="currentPage>1" v-on:click="currentPage--,pageClick()">
                    <button>上一页</button>
                </li>
                <li v-for="(item,index) in pagesItems" :class="{'active':currentPage===item}" v-on:click="btnClick(item), pageClick()">
                    {{item}}
                </li>
                <li class="page_last" v-if="currentPage!==totalPages" v-on:click="currentPage++,pageClick()">
                    <button>下一页</button>
                </li>
                <li class="allPage">共{{totalPages}}页</li>
                <li class="jump_count">到第<input class="jump_input" type="number" ref="jumppage"> 页</li>
                <li v-on:click="pageSkip()">确定</li>
            </ul>
        </div>
        <!--引入提示弹框-->
        <popup v-model="sendVal" :content="msgContent"></popup>

        <tjzbk v-if="showMask" :showMask.sync="showMask"/>

        <shtc v-if="showMask3" :showMask3.sync="showMask3" />
    </div>
</template>

<script>
  import reback from '../popup/reback';
  import popup from '../popup/popup';
  import tjzbk from '../popup/tjzbk'
  import shtc from '../popup/sh'
  export default {
    name: '',
    metaInfo: {
      title: ''
    },

    components: {
      popup,
      tjzbk,
      reback,
      shtc
    },
    data: () => ({
      filterRole: 'desc',
      usersData: [],
      params:[],
      userDataCount: 0,
      currentPage: 1,
      perPage: 10,
      sendVal: false,
      msgContent: '',
      showMask: false,
      showMask3: false,
    }),
    computed: {
      totalItems () {
        return this.userDataCount
      },
      totalPages () {
        return Math.ceil(this.totalItems / this.perPage)
      },
      pagesItems () {
        var left = 1
        var right = this.totalPages
        var arr = []
        if (this.totalPages >= 7) {
          if (this.currentPage > 4 && this.cur < this.totalPages - 3) {
            left = this.currentPage - 3
            right = this.currentPage + 3
          } else if (this.currentPage <= 4) {
            left = 1
            right = 7
          } else {
            left = this.totalPages - 6
            right = this.totalPages
          }
        }
        while (left <= right) {
          arr.push(left)
          left++
        }
        console.log(arr)
        return arr
      }
    },
    methods: {
      shtc(id,statue) {
        
           console.log(id);


          var that =this
        
        
            that.axios.post(`${this.devUrl}course/audit`, {
               id: id,status:statue
               })
            .then(function (response) {
             // console.log(response.data.code)

          var data = response.data

       
          console.log(data);
          if(data.code=="200"){
          //    that.sendVal = true
          // that.msgContent = '审核成功!'
           const link = "/zb/list"
      
      window.open(link, '_self')
          }

            });
                    
              












         
          
        
        //this.showMask3 = true
      },
      clickMark () {
        this.showMask = true
      },
      toEdit (id) {
        const link = '/zb/edit?id=' + id // 目标地址
        window.open(link, '_self')
      },
      btnClick (num) {
        if (num !== this.currentPage) {
          this.currentPage = num
        }
      },
      pageClick () {
        var that = this
        that.axios.post(`${this.devUrl}course/search`, that.getParamsToken({
          page: that.currentPage
        }))
          .then(function (response) {
            var data = response.data
            var datas = data.data
            if (data.code === 200) {
              that.userDataCount = datas.count
              that.usersData = datas.data
            }
          })
      },
      pageSkip () {
        var maxPage = this.totalPages
        var skipPage = Number(this.$refs.jumppage.value)
        if (!skipPage) {
          this.sendVal = true
          this.msgContent = '请输入跳转页码'
        } else if (skipPage < 1 || skipPage > maxPage) {
          this.sendVal = true
          this.msgContent = '您输入的页码超过页数范围了'
        } else {
          this.btnClick(skipPage)
          this.pageClick()
        }
      },
      search () {
        var that = this
        var searchCon = that.$refs.searchCon.value
        if (!searchCon) {
          that.sendVal = true
          that.msgContent = '请输入关键字'
        }
        that.axios.post(`${this.devUrl}course/search`, that.getParamsToken({
          courseName: searchCon
        }))
          .then(function (response) {
            var data = response.data
            var datas = data.data
            if (data.code === 200) {
              that.userDataCount = datas.count
              that.usersData = datas.data
            }
          })
      },
      setSort1(event) {
        var that = this
        var status = event.target.value
        /*that.axios.post(`${this.devUrl}course/search`, that.getParamsToken({*/
           that.axios.post(`${this.devUrl}course/search`, that.getParamsToken({
          status: status
        }))
          .then(function (response) {
            var data = response.data
            var datas = data.data
            if (data.code === 200) {
              that.userDataCount = datas.count
              that.usersData = datas.data
            }
          })
      },
      setSort (event) {
        var that = this
        var sortType = event.target.value
        /*that.axios.post(`${this.devUrl}course/search`, that.getParamsToken({*/
           that.axios.get(`${this.baseUrl}json/table-data.json`, that.getParamsToken({
          order: sortType
        }))
          .then(function (response) {
            var data = response.data
            var datas = data.data
            if (data.code === 200) {
              that.userDataCount = datas.count
              that.usersData = datas.data
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
    },
    created () {
      var that = this
      that.axios.post(`${this.devUrl}course/search`, that.getParamsToken({
        page: that.currentPage
      }))
        .then(function (response) {
          var data = response.data
          var datas = data.data
          if (data.code === 200) {
            that.userDataCount = datas.count
            that.usersData = datas.data
          }
        })
    }
  }
</script>

<style scoped>
    @import "zb.css";
    .z_zb_cent_boxr div{
        width:120px;
        height:30px;
        background:rgba(120,132,224,1);
        border-radius:4px;
        margin: 20px auto auto auto;
        font-size: 16px;
        font-family: SourceHanSansCN-Regular;
        font-weight: 400;
        color: rgba(255,255,255,1);
        line-height: 30px;
    }
    .z_zb_cent_boxr div img{
        width: 15px;
        height: 14px;
        vertical-align: middle;
        margin: auto 10px;
    }
</style>
