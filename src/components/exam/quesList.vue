<template>
  <div>

    <h5 class="mb-4 row z_flex">
      <div class="col-sm-11 col-xl-11 z_toptitl col-md-10">
        <i class="fas fa-map-marker-alt mr"></i>学生管理 <i class="ion ion-ios-arrow-forward mr ml"></i>
        <span class="c6B78DE">学生列表</span>
      </div>
      <reback></reback>
    </h5>

    <!-- Filters -->
    <div class="ui-bordered px-4 pt-4 mb-4">
      <div class="form-row align-items-center">
        <div class="col-md">
          <h5 class="font-weight-bold"><img class="mr" style="width:20px;vertical-align: bottom;" src="../../assets/search_icon.png">数据筛选</h5>
        </div>
       <!-- <div class="col-md col-xl-1">
          <b-btn class="btn-outline-primary" variant="outline-primary">高级搜索</b-btn>
        </div>-->
      </div>
      <div class="form-row align-items-center">
        <div class="col-md"><hr></div>
      </div>
      <div class="form-row align-items-center">
        <div class="col-md mb-4">
          <label class="form-label">输入查询</label>
          <b-input v-model="filterVerified" placeholder="用户ID/账号" ref="searchInput"/>
        </div>
        <div class="col-md mb-4">
          <label class="form-label">意向课程</label>
          <b-input v-model="levelSelected" placeholder="意向课程" ref="levelSelected"/>
        </div>
        <div class="col-md mb-4">
          <label class="form-label">注册时间</label>
          <flat-pickr v-model="filterLatestActivity" :config="{ altInput: true, animate: !isRTL, dateFormat: 'Y-m-d', altFormat: 'Y-m-d', mode: 'range' }" :placeholder="!isIEMode ? '' : null" ref="regTime"/>
        </div>
        <div class="col-md col-xl-1 mb-4">
          <b-btn class="btn-secondary" variant="secondary" :block="true"  @click.prevent="search()">查询</b-btn>
        </div>
        <div class="col-md col-xl-1 mb-4">
          <b-btn class="btn-outline-primary "  variant="outline-primary" @click.prevent="rest()">重置</b-btn>
        </div>
      </div>
    </div>
    <!-- / Filters -->

    <b-card no-body>
      <!-- Table controls -->
      <b-card-body>

        <div class="row">
          <div class="col">
            <h5 class="font-weight-bold"><img class="mr" style="width:16px;vertical-align: bottom;" src="../../assets/list_icon.png">数据列表</h5>
          </div>
          <div class="col">
            <b-btn variant="primary" class="d-inline-block w-auto float-sm-right" @click="add()"><i class="ion ion-md-add-circle-outline"></i>&nbsp; 添加</b-btn>
            <b-btn variant="primary" class="d-inline-block w-auto float-sm-right" @click="importExcel()"><i class="lnr lnr-upload"></i>&nbsp; 导出</b-btn>
          </div>
        </div>

      </b-card-body>
      <!-- / Table controls -->

      <!-- Table -->
      <hr class="border-light m-0">
      <div class="table-responsive">

        <b-table
                :items="usersData"
                :fields="fields"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :striped="true"
                :bordered="true"
                :current-page="currentPage"
                :per-page="perPage"
                class="card-table">

          <!--<template slot="name" slot-scope="data">
            <a href="javascript:void(0)">{{data.item.name}}</a>
          </template>-->

          <template slot="use_flag" slot-scope="data">
            <span class="fas fa-toggle-on"  @click.prevent="use_flag(data)" :class="[data.item.use_flag==1?'text-primary':'text-light']" style="font-size: 20px;"></span>
          </template>

          <template slot="role" slot-scope="data">
            <span v-if="data.item.role === 1">User</span>
            <span v-if="data.item.role === 2">Author</span>
            <span v-if="data.item.role === 3">Staff</span>
            <span v-if="data.item.role === 4">Admin</span>
          </template>

          <template slot="status" slot-scope="data">
            <b-badge variant="outline-success" v-if="data.item.status === 1">Active</b-badge>
            <b-badge variant="outline-danger" v-if="data.item.status === 2">Banned</b-badge>
            <b-badge variant="outline-default" v-if="data.item.status === 3">Deleted</b-badge>
          </template>
          <template slot="operates"  slot-scope="data">
            <div>
              <span class="btn-xs c6B78DE pointer-style" @click.prevent="detail(data)"><i class="ion ion-md-eye mr8"></i>查看</span>
              <span class="btn-xs c6B78DE pointer-style" @click.prevent="edit(data)"><img class="mr8" style="width:10px;vertical-align: middle;" src="../../assets/edit_icon.png">编辑</span>
              <span class="btn-xs cFF5E38 pointer-style" @click.prevent="remove(data)"><i class="ion ion-ios-trash mr8"></i>删除</span>
            </div>
          </template>
          <template slot="checkbox"  slot-scope="data">

            <label  @click.prevent="checkClick(data)">
              <b-check class="text-center"  v-model="data.item.checkbox"></b-check>
            </label>
          </template>
        </b-table>

      </div>

      <!-- Pagination -->
      <b-card-body class="pt-0 pb-3">

        <div class="row row-change-style">
         <!-- <div class="pt-3 active-select-style">
            <label @click.prevent="checkAllClick">
              <b-check class="text-muted" v-model="checkAllStatus">全选</b-check>
            </label>
          </div>-->
          <div class="text-right pt-3 page-list-style">
            <b-pagination class="justify-content-center justify-content-sm-end m-0 "
                          v-if="totalItems"
                          v-model="currentPage"
                          :total-rows="totalItems"
                          :per-page="perPage"
                          prev-text="上一页"
                          next-text="下一页"
                          size="sm"/>
            <!--last-text="最后一页" first-text="第一页"-->
          </div>
          <div class="text-left pt-3 page-totle-style">
            <span class="text-muted" v-if="totalItems">第{{ currentPage }}页 共{{ totalPages }}页</span>
          </div>
        </div>

      </b-card-body>
      <!-- / Pagination -->

    </b-card>
  </div>
</template>

<style src="@/vendor/libs/vue-flatpickr-component/vue-flatpickr-component.scss" lang="scss"></style>
<style>
  @import "../parents/parent.css";

</style>

<script>
import reback from '../popup/reback'
import flatPickr from 'vue-flatpickr-component'
export default {
  name: 'pages-user-list',
  metaInfo: {
    title: 'User list - Pages'
  },
  components: {
    flatPickr, reback
  },
  data: () => ({
    // Options
    searchKeys: ['id', 'name', 'parent_name', 'parent_mobile', 'school', 'reg_date'],
    sortBy: 'id',
    sortDesc: false,
    perPage: 10,
    fields: [
      { key: 'checkbox', label: '', tdClass: 'text-nowrap align-middle  py-3' },
      { key: 'id', label: 'ID', sortable: true, tdClass: 'align-middle' },
      { key: 'name', label: '学生姓名', sortable: true, tdClass: 'align-middle' },
      { key: 'parent_name', label: '家长姓名', sortable: true, tdClass: 'align-middle' },
      { key: 'parent_mobile', label: '家长手机号', sortable: true, tdClass: 'align-middle' },
      { key: 'school', label: '学校', sortable: true, tdClass: 'align-middle' },
      { key: 'intention', label: '意向课程', sortable: true, tdClass: 'align-middle' },
      { key: 'age', label: '年龄', sortable: true, tdClass: 'align-middle' },
      { key: 'reg_date', label: '注册时间', sortable: true, tdClass: 'align-middle' },
      // { key: 'use_flag', label: '启用', sortable: true, tdClass: 'align-middle' },
      { key: 'operates', label: '操作', tdClass: 'text-nowrap align-middle' }
    ],
    // Filters
    filterVerified: null,
    filterRole: '',
    filterLatestActivity: null,
    usersData: [],
    checkedData: [],
    originalUsersData: [],
    checkAllStatus: null,
    currentPage: 1,
    countData: null,
    levelSelected: ''
  }),
  computed: {
    totalItems () {
      return this.usersData.length
    },
    totalPages () {
      return Math.ceil(this.totalItems / this.perPage)
    }
  },
  methods: {
    filter (value) {
      const val = value.toLowerCase()
      const filtered = this.originalUsersData.filter(d => {
        return Object.keys(d)
          .filter(k => this.searchKeys.includes(k))
          .map(k => String(d[k]))
          .join('|')
          .toLowerCase()
          .indexOf(val) !== -1 || !val
      })
      this.usersData = filtered
      console.log(this.usersData)
    },
    //重置
    rest () {
      this.filterVerified = [],
              this.levelSelected = [],
              this.filterLatestActivity = []
    },
    search (active) {
      var searchInput = this.filterVerified
      var intention = this.levelSelected
      var startDate = ''
      var endDate = ''
      var regDate = this.filterLatestActivity
      var params = {}
      if (searchInput) {
        params['id'] = searchInput
      }
      if (intention) {
        params['intention'] = intention
      }
      if (regDate) {
        regDate = regDate.split('to')
        if (regDate.length > 1) {
          var startDate = regDate[0].trim()
          var endDate = regDate[1].trim()
        } else {
          var startDate = regDate[0].trim()
          var endDate = regDate[0].trim()
        }
        params['regDate'] = regDate
        params['startDate'] = startDate
        params['endDate'] = endDate
      }
      var that = this
      that.axios.post(`${this.devUrl}student/search`, that.getParamsToken(params))
        .then(function (response) {
          var data = response.data
          if (data.code === 200) {
            var datas = data.data
            that.usersData = datas.data
          } else {
            that.usersData = []
          }
        })
        .catch(function (error) {
          that.sendVal = true
          that.msgContent = error
        })
    },
    use_flag (row) {
      var that = this
      var useFlag = row.item.use_flag === 1 ? 2 : 1
      var id = row.item.id
      that.axios.post(`${this.devUrl}student/edit`, that.getParamsToken({
        id: id, useFlag: useFlag, name: row.item.name, parentName: row.item.parent_name, parentMobile: row.item.parent_mobile, gender: row.item.gender, birthday: row.item.birthday, school: row.item.school, origin: row.item.origin, intention: row.item.intention, province: row.item.province, city: row.item.city, region: row.item.region, street: row.item.street
      }))
        .then(function (response) {
          var data = response.data
          if (data.code === 200) {
            if (useFlag === 2) {
              row.item.use_flag = 0
            } else {
              row.item.use_flag = 1
            }
          } else {
            that.sendVal = true
            that.msgContent = data.error
          }
        })
        .catch(function (error) {
          that.sendVal = true
          that.msgContent = error
        })
    },
    checkClick (row) {
      var that = this
      var userData = that.usersData
      var checkedData = that.usersData
      var flag = false
      for (var i = 0; i < userData.length; i++) {
        if (row.item.id === userData[i]['id']) {
          userData[i]['checkbox'] = true
          console.log(row.item.id, userData[i]['id'])
        }
      }
      that.checkAllStatus = flag
    },
    checkAllClick () {
      var that = this
      var userData = that.usersData
      var flag = false
      if (that.checkedData.length !== 0) {
        that.checkedData = []
      }
      for (var i = 0; i < userData.length; i++) {
        if (!that.checkAllStatus) {
          userData[i]['checkbox'] = true
          that.checkedData.push(userData[i])
          flag = true
        } else {
          userData[i]['checkbox'] = false
          that.checkedData = []
          flag = false
        }
      }
      that.checkAllStatus = flag
    },
    importExcel () {
      var searchInput = this.filterVerified
      var intention = this.levelSelected
      var startDate = ''
      var endDate = ''
      var regDate = this.filterLatestActivity
      var str = ''
      if (searchInput) {
        str += '' + '&id=' + searchInput
      }
      if (intention) {
        str += '' + '&intention=' + intention
      }
      if (regDate) {
        regDate = regDate.split('to')
        if (regDate.length > 1) {
          var startDate = regDate[0].trim()
          var endDate = regDate[1].trim()
        } else {
          var startDate = regDate[0].trim()
          var endDate = regDate[0].trim()
        }
        str += '' + '&regDate=' + regDate + '&startDate=' + startDate + '&endDate=' + endDate
      }
      const link = `${this.devUrl}export/excel?module=student` + str// 目标地址
      window.open(link, '_self')
    },
    detail (row) {
      const id = row.item.id
      const link = '/students/edit?type=edit&id=' + id // 目标地址
      window.open(link, '_self')
    },
    add () {
      const link = '/students/add' // 目标地址
      window.open(link, '_self')
    },
    edit (row) {
      const id = row.item.id
      const link = '/students/edit?type=edit&id=' + id // 目标地址
      window.open(link, '_self')
    },
    remove (row) {
      var userData = this.usersData
      var ids = []
      var that = this
      ids.push(row.item.id)
      let postData = {
        ids: JSON.stringify(ids)
      }
      that.axios.post(`${this.devUrl}student/del`, that.getParamsToken(postData))
        .then(function (response) {
          var data = response.data
          if (data.code === 200) {
              location.reload()
            // userData.splice(row, 1)
          } else {
            that.sendVal = true
            that.msgContent = data.error
          }
        })
        .catch(function (error) {
          that.sendVal = true
          that.msgContent = error
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
    that.axios.post(`${this.devUrl}student/search`, that.getParamsToken({
      limit: 999999
    },{
      headers: {
        'Content-Type':"application/json;charset=utf-8"
      },
      withCredentials : true
    }))
      .then(function (response) {
        var data = response.data
        var datas = data.data
        if (data.code === 200) {
          that.countData = datas.count
          that.usersData = datas.data
          that.originalUsersData = that.usersData.slice(0)
        }
      })
  }
}
</script>
