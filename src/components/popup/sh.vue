
<template>
    <div class="z_tcbox" v-show="showMask3">
        <div class="row z_tcbox_div z_cx z_cxqd">
            <div class="z_tcbox_top">
                <div class="z_tcbox_top_tips">
                    确认<br>提示
                </div>
                <p @click="toggle()"><img src="../../assets/closetc.png" width="20" height="20"> </p>
            </div>
            <div class="z_tcbox_centent">
                <div class="sh_tips">
                    是否确认该课程审核通过？
                </div>
                <div class="z_btn_submit">
                    <button class="z_messg_submit" @click="submit">确认</button>
                    <button @click="toggle()">取消</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

  export default {
    props: {
      showMask3: {
        type: Boolean
      },
    },
    components: {

    },

    data () {
      return {

      }
    },
    mounted () {

    },
    methods: {
        submit(){

           
           
        },

      toggle () {
        // 下面的语句配合开头写的 .sync 就会更新父组件中的 panelShow 变量
        this.$emit('update:showMask3', false)
      },


    }

  }
</script>

<style scoped>

    .z_cx{
        width: 500px;
        height:330px;
        padding-bottom: 30px;
    }

    .z_tcbox .z_tcbox_centent{
        width: 85%;
        margin: 0 auto;
    }
    .z_tcbox_centent .z_btn_submit{
        width:100%;

    }
    .sh_tips{
        height: 175px;
        line-height: 175px;
        font-size:18px;
        font-family:SourceHanSansCN-Regular;
        font-weight:400;
        color:rgba(85,85,85,1);
        text-align: center;
    }
</style>
