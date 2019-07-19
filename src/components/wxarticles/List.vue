<template>
  <div>
    <!-- <h5 class="font-weight-bold py-3 mb-4">
       <i class="fas fa-map-marker-alt mr5"></i>营销活动 <i class="ion ion-ios-arrow-forward mr5 ml5"></i>
       <span class="c6B78DE">营销文章列表</span>
     </h5>-->
    <h5 class="mb-4 row z_flex">
      <div class="col-sm-11 col-xl-11 z_toptitl">
        <i class="fas fa-map-marker-alt mr5"></i>营销活动 <i class="ion ion-ios-arrow-forward mr5 ml5"></i>
        <span class="c6B78DE">营销文章列表</span>
      </div>
      <router-link class="col-sm-1 col-xl-1 z_toptitr" to="/home/home">
        <span><img src="../../assets/reback.png"  class="mr" width="14">返回</span>
      </router-link>
    </h5>

    <!-- Filters -->
    <div class="ui-bordered px-4 pt-4 mb-4" v-show="lishow">
      <div class="form-row align-items-center">
        <div class="col-md">
          <h5 class="font-weight-bold"><i class="ion ion-md-search"></i>数据筛选</h5>
        </div>
      <!--  <div class="col-md col-xl-2">
          <b-btn class="btn-outline-primary" variant="outline-primary">高级搜索</b-btn>
        </div>-->
      </div>
      <div class="form-row align-items-center">
        <div class="col-md"><hr></div>
      </div>
      <div class="form-row align-items-center">
        <div class="col-md mb-4">
          <label class="form-label">文章名称</label>
          <b-input v-model="articleName" placeholder="请输入文章名称"/>
        </div>
        <div class="col-md mb-4">
          <label class="form-label">文章类型</label>
          <b-select v-model="articleType" :options="['素质教育']" />
        </div>
        <div class="col-md mb-4">
          <label class="form-label">审核状态</label>
          <b-select v-model="status" :options="['不限','待审核', '审核通过', '未通过', '已下架']" />
        </div>
        <div class="col-md col-xl-1 mb-4">

          <b-btn class="btn-secondary" variant="secondary" :block="true"  @click.prevent="search()">查询</b-btn>
        </div>
        <div class="col-md col-xl-1 mb-4">

          <b-btn class="btn-outline-primary"  variant="outline-primary">重置</b-btn>
        </div>
      </div>
    </div>
    <!-- / Filters -->

    <b-card no-body v-show="lishow">
      <!-- Table controls -->
      <b-card-body>

        <div class="row">
          <div class="col">
            <h5 class="font-weight-bold"><img class="mr" style="width:16px;vertical-align: bottom;" src="../../assets/list_icon.png">数据列表</h5>
          </div>
          <div class="col">
            <b-btn variant="primary" class="d-inline-block w-auto float-sm-right" >&nbsp;
             <a href="http://test2.anxe.com.cn/web/index.php?c=site&a=entry&id=0&do=ueditor&m=tech_superarticle#" @click="showIframe" target="showHere"> <i class="ion ion-md-add-circle-outline"></i>添加</a>
            </b-btn>
            <b-btn variant="primary" class="d-inline-block w-auto float-sm-right"><i class="lnr lnr-download"></i>&nbsp; 导入</b-btn>
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

          <template slot="is_show" slot-scope="data">
            <span class="fas"  @click.prevent="is_show(data)" :class="{'fa-toggle-on text-primary': data.item.is_show, 'fa-toggle-on text-light': !data.item.is_show}" style="font-size: 20px;"></span>
          </template>

          <template slot="role" slot-scope="data">
            <span v-if="data.item.role === 1">User</span>
            <span v-if="data.item.role === 2">Author</span>
            <span v-if="data.item.role === 3">Staff</span>
            <span v-if="data.item.role === 4">Admin</span>
          </template>

          <template slot="status" slot-scope="data">
            <b-badge variant="outline-primary" v-if="data.item.status === 1">待审核</b-badge>
            <b-badge variant="outline-danger" v-if="data.item.status === 2">未通过</b-badge>
            <b-badge variant="outline-success" v-if="data.item.status === 3">已审核</b-badge>
          </template>
          <template slot="operates"  slot-scope="data">
            <div>
              <!--<span class="btn-xs c6B78DE pointer-style"><img class="mr8" style="width:10px;vertical-align: middle;" src="../../assets/eye_see_icon.png">查看</span>-->
              <span class="btn-xs c6B78DE pointer-style"  @click="edit"><img class="mr8" style="width:10px;vertical-align: middle;" src="../../assets/link_icon.png">链接</span>
              <span class="btn-xs cFF5E38 pointer-style" @click.prevent="remove(data)"><i class="ion ion-ios-trash mr8"></i>删除</span>
            </div>
          </template>
          <template slot="checkbox"  slot-scope="data">
            <b-check class="text-center" v-model="checkAllStatus"></b-check>
            <!--<b-check class="text-center"  @click.prevent="checkClick(data)"></b-check>-->
          </template>
        </b-table>

      </div>

      <!-- Pagination -->
      <b-card-body class="pt-0 pb-3">

        <div class="row row-change-style">
          <!--  <div class="pt-3 active-select-style">
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
    <qdtc v-if="showMask3" :showMask3.sync="showMask3"  :chanelId.sync="chanelId"/>



    <iframe v-show="iframeState" id="show-iframe" frameborder=0 name="showHere" scrolling=auto src="" style="width: 100%;height: 760px;"></iframe>


  </div>
</template>

<style src="@/vendor/libs/vue-flatpickr-component/vue-flatpickr-component.scss" lang="scss"></style>
<style>
  @import "../parents/parent.css";
  @import "../dashboards/dash.css";

</style>

<script>
    import qdtc from '../popup/qdtc'
import flatPickr from 'vue-flatpickr-component'
export default {
  name: 'pages-user-list',
  metaInfo: {
    title: 'User list - Pages'
  },
  components: {
    flatPickr,
      qdtc
  },
  data: () => ({

    iframeState:true,
    lishow:true,

    // Options
    searchKeys: ['id', 'account', 'name', 'class', 'created_at', 'is_show'],
    sortBy: 'id',
    sortDesc: false,
    perPage: 10,
    fields: [
      { key: 'checkbox', label: '', tdClass: 'text-nowrap align-middle  py-3' },
      { key: 'id', label: 'ID', sortable: true, tdClass: 'align-middle' },
      { key: 'creator', label: '发布人', sortable: true, tdClass: 'align-middle' },
      { key: 'name', label: '文章名称', sortable: true, tdClass: 'align-middle' },
      { key: 'status', label: '状态', sortable: true, tdClass: 'align-middle' },
      { key: 'class', label: '文章类型', sortable: true, tdClass: 'align-middle' },
      { key: 'created_at', label: '发布时间', sortable: true, tdClass: 'align-middle' },
      // { key: 'is_show', label: '是否显示', sortable: true, tdClass: 'align-middle' },
      { key: 'operates', label: '操作', tdClass: 'text-nowrap align-middle' }
    ],
    // Filters
    filterVerified: null,
    filterLatestActivity: null,
    usersData: [],
    originalUsersData: [],
    checkAllStatus: null,
    currentPage: 1,
    status: '不限',
    articleName: '',
    articleType: null,
    showMask3: false
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
    showIframe(){
      this.lishow = false;
      this.iframeState = true;

    },
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
    },
    search (active) {
      var that = this
      var articleName = that.articleName
      var articleType = that.articleType
      var status = that.status
      var params = {}
      if (articleName) {
        params['name'] = articleName
      }
      if (articleType) {
        params['articleType'] = articleType
      }
      if (status) {
        params['status'] = that.getStatusNum(status)
      }
      var searchParams = that.getParamsToken(params)
      that.axios.post(`${this.devUrl}activity/search`, searchParams)
        .then(function (response) {
          var data = response.data
          if (data.code === 200) {
            var datas = data.data
            that.usersData = datas.data
          } else {
            that.usersData = []
          }
        })
    },
    is_show (row) {
      var that = this
      var isShow = row.item.is_show
      var id = row.item.id
      /* that.axios.get(`${this.devUrl}activity/search`, {
          id: id
        })
          .then(function (response) {
            var data = response.data
            if (data.code === 200) {
              if (isShow) {
                row.item.is_show = 0
              } else {
                row.item.is_show = 1
              }
            } else {
              alert(data.error)
            }
          })
          .catch(function (error) {
            alert(error)
          }) */
    },
    detail (row) {
      alert(`Detail: ${row.item.id} ${row.item.account}`)
    },
    edit (row) {
      // this.chanelId = 123
      this.showMask3 = true
    },
    remove (row) {
      var userData = this.usersData
      var ids = []
      var that = this
      ids.push(row.item.id)
      let postData = {
        ids: JSON.stringify(ids)
      }
      that.axios.post(`${this.devUrl}activity/del`, that.getParamsToken(postData))
        .then(function (response) {
          var data = response.data
          if (data.code === 200) {
            userData.splice(row, 1)
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
    },
    getStatusNum (status) {
      var arr = {
        '不限': 0,
        '未审核': 1,
        '审核通过': 2,
        '未通过': 3,
        '已下架': 4
      }
      return arr[status]
    }
  },
  created () {
    var that = this
    that.axios.post(`${this.devUrl}activity/search`, that.getParamsToken({
      limit: 999999
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
