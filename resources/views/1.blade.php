{{--<!DOCTYPE html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <title>Title</title>--}}
{{--</head>--}}
{{--<script>--}}
{{--    var _hmt = _hmt || [];--}}
{{--    (function() {--}}
{{--        var hm = document.createElement("script");--}}
{{--        hm.src = "https://hm.baidu.com/hm.js?b18c500b4a6cf57931b4deb2c064fb90";--}}
{{--        var s = document.getElementsByTagName("script")[0];--}}
{{--        s.parentNode.insertBefore(hm, s);--}}
{{--    })();--}}
{{--</script>--}}
{{--<body>--}}
{{--<h1>test</h1>--}}
{{--</body>--}}
{{--</html>--}}
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>js计算页面停留时间_www.jbxue.com</title>
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.js"></script>
</head>
<body onload="init(); window.setTimeout('show_secs()',1);"  onbeforeunload="return myFunction()">
<!-- 用<body onload="init(); window.setTimeout('show_secs()',1);">替换原有的<body> -->
<!--将以下代码加入html的<body></body>之间-->
<script language="javascript">

    var ap_name = navigator.appName;
    var ap_vinfo = navigator.appVersion;
    var ap_ver = parseFloat(ap_vinfo.substring(0,ap_vinfo.indexOf('(')));// 获取版本号
    var time_start = new Date();
    var clock_start = time_start.getTime();
    var dl_ok=false;

    function init (){
        if(ap_name=="netscape" && ap_ver>=3.0)
            dl_ok=true;
        return true;
    }

    //已经打开页面多少秒
    function get_time_spent (){
        var time_now = new Date();
        return((time_now.getTime() - clock_start)/1000);
    }

    function show_secs (){
        // show the time user spent on the side
        var i_total_secs = Math.round(get_time_spent());
        var i_secs_spent = i_total_secs % 60;
        var i_mins_spent = Math.round((i_total_secs-30)/60);//四舍五入，超60s，大于0.5，四舍五入就是1min
        var s_secs_spent = "" + ((i_secs_spent>9) ? i_secs_spent : "0" + i_secs_spent);//改显示格式：个位数-> 0+个位数，如7->07
        var s_mins_spent ="" + ((i_mins_spent>9) ? i_mins_spent : "0" + i_mins_spent);
        document.fm0.time_spent.value = s_mins_spent + ":" + s_secs_spent;  //把值放入form中name为time_spent的input中
        window.setTimeout('show_secs()',1000);   //每隔1s刷新一次input里的值
    }

    //----------------------beforeunload使用方法一：（需要去掉body标签里的beforeunload="myFunction()"）------------------------------------
    //在关闭页面时弹出确认提示窗口
    //$(window).bind('beforeunload', function(){
    //	//alert(09999);//凡是alert在此函数里被阻止，Blocked alert('09999') during beforeunload.
    //	s1(2000);
    //
    //	console.log("您在网站"+ document.URL+"停留时间（分：秒）："+document.fm0.time_spent.value);
    //
    //     window.event.returnValue="1.确定要退出本页吗？";
    //
    //});

    //----------------------beforeunload使用方法二：（不去掉body标签里的beforeunload="myFunction()"也可以，还是执行此方法二，而不是方法三）------------------------------------
    //测试：谷歌浏览器：刷新时只执行最后一行代码，关闭页面时三行代码都执行
    window.onbeforeunload = function(event){
        //alert(09999);//凡是alert在此函数里被阻止，Blocked alert('09999') during beforeunload.
        s1(2000);
        var arr = [0, 1, 2, 3];
        localStorage.setItem("num", arr);//存储数据
        console.log(localStorage.getItem("num"));
        console.log("您在网站"+ document.URL+"停留时间（分：秒）："+document.fm0.time_spent.value);
        if(window.is_confirm !== false){
            window.event.returnValue="2.确定要退出本页吗？";  //刷新页面时只执行此行
        }
    };

    //----------------------beforeunload使用方法三：（需要在body标签里的加上beforeunload="myFunction()"）------------------------------------
    //测试：谷歌浏览器：刷新时只执行最后一行代码，关闭页面时三行代码都执行
    function myFunction(){
        //alert(09999);//凡是alert在此函数里被阻止，Blocked alert('09999') during beforeunload.
        s1(2000);

        console.log("您在网站"+ document.URL+"停留时间（分：秒）："+document.fm0.time_spent.value);

        window.event.returnValue="3.确定要退出本页吗？";  //刷新页面时只执行此行

    };


    //js中的暂停方法一
    function sleep(numberMillis) {
        var now = new Date();
        var exitTime = now.getTime() + numberMillis;
        while (true) {
            now = new Date();
            if (now.getTime() > exitTime)
                return;
        }
    }
    function s(){
        sleep(2000);
        alert("您在网站"+ document.URL+"停留时间（分：秒）："+document.fm0.time_spent.value);
    }
    //js中的暂停方法二
    function s1(sec){
        setTimeout(function(){
            alert("您在网站"+ document.URL+"停留时间（分：秒）："+document.fm0.time_spent.value);
        },sec);
    }
</script>
<form name="fm0" onsubmit="0">
    <font color="#888888" size="-1">您在本网页的停留时间:</font>
    <input type="text" name="time_spent" size=7 onfocus="this.blur()">
    <input type="button" value="暂停2秒" onclick="s1(2000)" />
    <a href="http://blog.csdn.net/davislien/article/details/47685831">参考链接</a>
</form>
</body>
</html>