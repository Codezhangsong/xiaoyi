<template>
  <nav class="layout-footer footer" :class="getLayoutFooterBg()">
    <div class="container">
      <img src="../assets/logo2.png">
      <p>校翼教育机构运营平台产品v1.0<br>
        Copyright © xiaoyi.edutagre.com.cn, All Rights Reserved.</p>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'app-layout-footer',

  methods: {
    getLayoutFooterBg () {
      return `bg-${this.layoutFooterBg}`
    }
  }
}
</script>
<style>
  .bg-footer-theme{
    background:rgba(239,241,245,1) !important;
  }
  .container{
    width: 520px;
    margin:0 auto;
    padding-top: 20px;
  }
  .container p{
    text-align: left;
    display: inline-block;
    margin-left: 20px;
    color: #999999;

  }
  .container img{
    margin-top: -20px;
  }
</style>
