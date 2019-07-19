<template>
  <div>

    <h5 class="mb-4 row z_flex">
      <div class="col-sm-11 col-xl-11 z_toptitl">
        <i class="fas fa-map-marker-alt mr5"></i>考试系统 <i class="ion ion-ios-arrow-forward mr5 ml5"></i>
        <span class="c6B78DE">会员管理 </span><i class="ion ion-ios-arrow-forward mr5 ml5"></i>
        <span class="c6B78DE">会员列表</span>
      </div>
      <div class="col-sm-1 col-xl-1 z_toptitr">
        <span><img src="../../assets/reback.png"  class="mr" width="14">返回</span>
      </div>
    </h5>

    <b-tabs class="nav-tabs-top nav-responsive-sm">
      <b-card header="修改标签" header-tag="h6" class="mb-4">
        <b-form action="">
          <b-form-group horizontal :label-cols="2">
            <div slot="label" class="text-sm-right pr-sm-2">姓名</div>
            <b-input v-model="usersData.name" placeholder="请输入姓名" ref="tag_name" />
          </b-form-group>
          <b-form-group horizontal :label-cols="2">
            <div slot="label" class="text-sm-right pr-sm-2">手机号</div>
            <b-input v-model="usersData.mobile" placeholder="请输入手机号" ref="mobile" />
          </b-form-group>
          <b-form-group horizontal :label-cols="2">
            <div slot="label" class="text-sm-right pr-sm-2">学校</div>
            <b-select v-model="schoolId">
              <option value="">不限</option>
              <option v-for="(item,index) in schoolList"  :value="item.id">{{ item.name }}</option>
            </b-select>
          </b-form-group>
          <b-form-group horizontal :label-cols="2">
            <div slot="label" class="text-sm-right pr-sm-2">班级</div>
            <b-select v-model="classId">
              <option value="">不限</option>
              <option v-for="(item,index) in classList"  :value="item.id">{{ item.name }}</option>
            </b-select>
          </b-form-group>
          <b-form-group horizontal :label-cols="2">
            <div slot="label" class="text-sm-right pr-sm-2">密码</div>
            <b-input v-model="password" type="password" placeholder=""  ref="password"/>
          </b-form-group>
          <b-form-row>
            <div class="col-sm-10 ml-sm-auto">
              <b-btn variant="primary" @click.prevent="editPost(usersData)">提交</b-btn>
              <b-btn variant="default">重置</b-btn>
            </div>
          </b-form-row>
        </b-form>
      </b-card>
    </b-tabs>
    <!--引入提示弹框-->
    <popup v-model="sendVal" :content="msgContent"></popup>
  </div>
</template>
<!-- Page -->
<style src="@/vendor/styles/pages/users.scss" lang="scss"></style>
<style>

  @import "../dashboards/dash.css";
  @import "../parents/edit.css";
</style>

<script>
import popup from '../popup/popup'
export default {
  name: 'pages-user-edit',
  metaInfo: {
    title: 'User edit - Pages'
  },
  components: {
    popup
  },
  data: () => ({
    usersData: {
      is_show: 'true'
    },
    sendVal: false,
    msgContent: '',
    schoolList: {},
    classList: {},
    schoolId: null,
    classId: null,
    password: ''
  }),
  methods: {
    editPost (data) {
      var that = this
      var id = that.$route.query.id
      var name = that.usersData.name
      var schoolId = that.schoolId
      var schoolList = that.schoolList
      var classId = that.classId
      var classList = that.classList
      var password = that.password
      var school = ''
      var classic = ''
      for (var i = 0; i < schoolList.length; i++) {
        if (schoolList[i]['id'] === schoolId) {
          school = schoolList[i]['name']
        }
      }
      for (var i = 0; i < classList.length; i++) {
        if (classList[i]['id'] === classId) {
          classic = classList[i]['name']
        }
      }
      var mobile = that.usersData.mobile
      var postData = {
        id: id, name: name, mobile: mobile, schoolId: schoolId, school: school, classId: classId, class: classic
      }
      if (password) {
        postData['password'] = password
      }
      that.axios.post(`${this.devUrl}exam/editAdmin`, that.getParamsToken(postData))
        .then(function (response) {
          var data = response.data
          if (data.code === 200) {
            that.sendVal = true
            that.msgContent = '编辑成功'
            const link = '/members/list'
            window.open(link, '_self')
          } else {
            that.sendVal = true
            that.msgContent = data.msg
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
    var id = this.$route.query.id
    var that = this
    that.axios.post(`${this.devUrl}exam/search`, that.getParamsToken({
      id: id
    }))
      .then(function (response) {
        var data = response.data
        var datas = data.data.data[0]
        if (data.code === 200) {
          that.usersData = datas
          that.schoolId = datas.school_id
          that.classId = datas.class_id
        }
      })
    that.axios.post(`${this.devUrl}school/search`, that.getParamsToken({
      limit: 9999
    }))
      .then(function (response) {
        var data = response.data
        var datas = data.data.data
        if (data.code === 200) {
          that.schoolList = datas
        }
      })
    that.axios.get(`${this.baseUrl}json/class-data.json`, that.getParamsToken({}))
      .then(function (response) {
        var data = response.data
        var datas = data.data.data
        if (data.code === 200) {
          that.classList = datas
        }
      })
  }
}
</script>
