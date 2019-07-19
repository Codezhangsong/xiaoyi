<template>
    <div class="z_tcbox z_exam_tc" v-show="showMask7">
        <div class="row z_tcbox_div z_tjzbk">
            <div class="z_tcbox_top">
                <div class="z_tcbox_top_tips">
                    添加<br>考試
                </div>
                <p @click="toggle()">&times;</p>
            </div>
            <div class="z_tcbox_centent">
                <form role="form">
                    <div class="z_cx_box_lable mb-3">
                        <span class="z_drs">*</span>
                        <label>考试名称</label>
                        <input v-model="name"  ref="uname" value="">
                    </div>
                    <div class="z_cx_box_lable mb-3 z_tc_datebox">
                        <span class="z_drs">*</span>
                        <label>考试时间</label>
                       <div class="z_tc_select z_tc_date">
                           <datepicker v-model="sday" class=""
                                        :bootstrapStyling="true"
                                        :monday-first="false"
                                        :full-month-name="false"
                                        placeholder="请选择开始时间"
                                        :calendar-button="true"
                                        calendar-button-icon="ion ion-md-calendar"
                                        :clear-button="true"
                                        format="yyyy-MM-dd" ref="sday" />
                       </div>
                    </div>



                    <div class="z_cx_box_lable mb-3 z_tc_datebox">
                        <span class="z_drs">*</span>
                        <label>结束时间</label>
                        <div class="z_tc_select z_tc_date">
                            <datepicker  v-model="eday"  class=""
                                         :bootstrapStyling="true"
                                         :monday-first="false"
                                         :full-month-name="false"
                                         placeholder="请选择结束时间"
                                         :calendar-button="true"
                                         calendar-button-icon="ion ion-md-calendar"
                                         :clear-button="true"
                                         format="yyyy-MM-dd" ref="eday" />
                        </div>
                    </div>
                    <div class="z_cx_box_lable mb-3">
                        <span class="z_drs">*</span>
                        <label>试卷名称</label>
                        <b-select v-model="slid" ref="slid"  class="z_tc_select">
                            <option value="0">请选择</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                        </b-select>
                    </div>

                </form>
                <div class="z_btn_submit">
                    <!-- <button class="z_messg_submit">保存草稿</button>-->
                    <button class="z_messg_submit"  @click="addPost()">确定发布</button>
                    <button @click="toggle()">关闭</button>
                </div>
            </div>
        </div>
    </div>
</template>
<style src="@/vendor/libs/vuejs-datepicker/vuejs-datepicker.scss" lang="scss"></style>
<script>
    import flatPickr from 'vue-flatpickr-component'
    import Datepicker from 'vuejs-datepicker'
    export default {
        name: '',
        data () {
            return {

            }
        },
        props: {
            showMask7: {
                type: Boolean
            }
        },
        components: {

            Datepicker,
            flatPickr,
        },
        methods: {
            addPost(){
               
                var that = this
                var name = that.name
               // var stime = that.sday
               // var etime = that.eday
                var stime =1;
                var etime =2;
                 var slid = that.slid
                // that.axios.get(`${this.baseUrl}json/table-data.json`, that.getParamsToken({
                that.axios.post(`${this.devUrl}exam/examination/`, that.getParamsToken({
                    ex_name:name,
                    start_time:stime,
                    end_time:etime,
                    text_exam_id:slid
                }))
                    .then(function (response) {
                    var data = response.data
                    var datas = data.data
                    if (data.code === 200) {
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
                },
            toggle () {
                // 下面的语句配合开头写的 .sync 就会更新父组件中的 panelShow 变量
                this.$emit('update:showMask7', false)
            },created () {
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
    }
</script>

<style scoped>
    @import "../examcom/tc.css";
    .z_cx_box_lable input{
        width: 78% !important;
        margin-left: 0;
    }
    .z_tc_date{
        position: relative;
        top: -40px;
        left: 21%;
    }
    .z_tc_datebox{
        margin-top: 30px!important;
        height: 30px;
    }



</style>
