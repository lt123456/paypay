<?php if (!defined('THINK_PATH')) exit();?><!Doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo C('WEB_SITE_TITLE');?></title>
    <meta name="keywords" content="<?php echo C('WEB_SITE_KEYWORD');?>">
    <meta name="description" content="<?php echo c('WEB_SITE_DESCRIPTION');?>">
    <link rel="stylesheet" href="/Public/Home/css/pc.css">
    <script type="text/javascript" src="/Public/Home/js/jquery.js"></script>
    <script type="text/javascript" src="/Public/Home/js/layer/layer.js"></script>
    <link rel="stylesheet" href="/Public/Home/js/layer.css" id="layui_layer_skinlayercss">
    <script>
        var zuidi = 10;
        var zuida = 3000;
    </script>
</head>
<body>
<form method="post" id="pay">
    <input type="hidden" name="wether" value="Zxcvbnm123456">
    <div class="header">
        <div class="logo">
            <img src="/Public/Home/images/pc/logo.png" align=""></div>
        <div class="online-service">
            <a href="<?php echo C('CALL_KF_URL');?>" target="_blank">
                <img src="/Public/Home/images/pc/header_04.png" align=""></a>
        </div>
    </div>
    <div class="content">
        <h2>支持【<span style="color: #ff0000;">手机端</span>】<span style="color:#05a11f">【微信扫码】</span>、<span style="color:#fa00d4">【支付宝】</span>在线支付最高单笔！</h2><p>扫一扫支付，手机也能支付，一键入款，立即到账!<br>支付流程：输入并确认正确的会员账号→输入存款额度→点击确认支付→付款成功后<span style="color: #ff0000;">1~10秒</span>自动到账；<br>支付宝 /微信 存款金额范围为（<span style="color: rgb(255, 0, 0);">10</span><span style="color: #ff0000;">~5000元</span>）, 需要大额入款可分多次存入或使用其它方式存款；<br></p>          <table class="content-table">
        <tbody>
        <tr>
            <td class="title">会员账号：</td>
            <td class="inputtd">
                <input placeholder="请填写快速充值中心游戏账户（*）" name="username" value="<?php echo $_COOKIE['onethink_home_username'];?>" id="username" type="text" class="table-input" onpaste="return false" onkeyup="value=value.replace(/[^\w\.\/]/ig,&#39;&#39;)"></td>
            <td align="center" style="color: #e60012;">*必填</td></tr>
        <tr>
            <td class="title">确认账号：</td>
            <td>
                <input placeholder="请认真填写快速充值中心游戏账户，否则无法正常充值（*）" name="re_username" value="<?php echo $_COOKIE['onethink_home_username'];?>" id="rusername" type="text" class="table-input" onpaste="return false" onkeyup="value=value.replace(/[^\w\.\/]/ig,&#39;&#39;)"></td>
            <td align="center" style="color: #e60012;">*必填</td></tr>
        <tr>
            <td class="title">支付类型：</td>

            <td id="getNewBackgroup" style="padding: 0px 15px;"><!-- 微信开始 -->

                <?php if(is_array($payments)): $i = 0; $__LIST__ = $payments;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label class="pay-label">
                        <?php switch($vo["paytype"]): case "0": ?><input type="radio" name="pay_type"  SB_type="WEIXIN" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="2" checked="checked">
                                <label></label>
                                <img src="/Public/Home/images/pc/wx.jpg" alt="" style="height: 40px; width: auto; display: inline-block; position: relative;">
                                <span style="font-size: 20px; height: 36px; display: inline-block; position: relative;">微信扫码</span><?php break;?>
                            <?php case "1": ?><input type="radio" name="pay_type"  SB_type="ALIPAY" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="2" checked="checked">
                                <label></label>
                                <img src="/Public/Home/images/pc/alipay.jpg" alt="" style="height: 40px; width: auto; display: inline-block; position: relative;">
                                <span style="font-size: 20px; height: 36px; display: inline-block; position: relative;">支付宝扫码</span><?php break;?>
                            <?php case "2": ?><input type="radio" name="pay_type"  SB_type="WANGYIN" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="2">
                                <label></label>
                                <img src="/Public/Home/images/pc/bank.jpg" alt="" style="height: 40px; width: auto; display: inline-block; position: relative;">
                                <span style="font-size: 20px; height: 36px; display: inline-block; position: relative;">网银扫码</span><?php break;?>
                            <?php case "3": ?><input type="radio" name="pay_type"  SB_type="WEIXIN" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="6" >
                                <label></label>
                                <img src="/Public/Home/images/pc/wx.jpg" alt="" style="height: 40px; width: auto; display: inline-block; position: relative;">
                                <span style="font-size: 20px; height: 36px; display: inline-block; position: relative;">平台微信扫码</span><?php break;?>
                            <?php case "4": ?><input type="radio" name="pay_type"  SB_type="ALIPAY" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="1" >
                                <label></label>
                                <img src="/Public/Home/images/pc/alipay.jpg" alt="" style="height: 40px; width: auto; display: inline-block; position: relative;">
                                <span style="font-size: 20px; height: 36px; display: inline-block; position: relative;">平台微信支付宝扫码</span><?php break;?>
                            <?php case "5": ?><input type="radio" name="pay_type"  SB_type="WANGYIN" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="3" >
                                <label></label>
                                <img src="/Public/Home/images/pc/bank.jpg" alt="" style="height: 40px; width: auto; display: inline-block; position: relative;">
                                <span style="font-size: 20px; height: 36px; display: inline-block; position: relative;">平台微信支付宝扫码</span><?php break;?>
                            <?php case "6": ?><input type="radio" name="pay_type"  SB_type="QQ" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="5" >
                                <label></label>
                                <img src="/Public/Home/images/pc/qq.jpg" alt="" style="height: 40px; width: auto; display: inline-block; position: relative;">
                                <span style="font-size: 20px; height: 36px; display: inline-block; position: relative;">QQ扫码</span><?php break; endswitch;?>
                    </label><?php endforeach; endif; else: echo "" ;endif; ?>

            </td>
            <td align="center" style="color: #e60012;">*必选</td></tr>
        <!--<tr id="zhifubaozhanghao" style="/* display: none; */">-->
            <!--<td class="title">支付宝昵称：</td>-->
            <!--<td>-->
                <!--<input placeholder="请认真填写支付宝昵称，否则财务无法第一时间入款" value="" type="text" class="table-input oclass" onpaste="return false"></td>-->
            <!--<td align="center" style="color: #e60012;">*必填</td></tr>-->
        <!--<tr id="weixinzhanghao" style="/* display: none; */">-->
            <!--<td class="title">微信昵称：</td>-->
            <!--<td>-->
                <!--<input placeholder="请认真填写微信昵称，否则财务无法第一时间入款" value="" type="text" class="table-input oclass" onpaste="return false"></td>-->
            <!--<td align="center" style="color: #e60012;">*必填</td></tr>-->
        <!--<tr>-->
        <!--</tr><tr id="qqzhanghao" style="/* display: none; */">-->
            <!--<td class="title">QQ帐号：</td>-->
            <!--<td>-->
                <!--<input placeholder="请认真填写QQ帐号，否则财务无法第一时间入款" value="" type="text" class="table-input oclass" onpaste="return false"></td>-->
            <!--<td align="center" style="color: #e60012;">*必填</td></tr>-->
        <tr>
            <td class="title">确认额度：</td>
            <td>
                <input placeholder="最低存款10元，最高单笔3000元" name="order_amount" type="text" class="table-input" id="coin" onkeyup="value=this.value.replace(/[^\d]+/g,&#39;&#39;)">
                <p>
                    <font color="red">温馨提醒:</font>入款时请不要充值整数，例如：1001、1089等不为整数的金额可提高充值成功率！</p></td>
            <td align="center" style="color: #e60012;">*必填</td></tr>
        <tr>
        </tr><tr>
            <td class="title">快捷额度：</td>
            <td>
                <?php
 $amount = explode(',',C('RANDOM_AMOUNT')); if(!is_array($amount)){ $amount = array('202','507','898','1024','1588','2043','2089'); } ?>
                <?php if(is_array($amount)): $i = 0; $__LIST__ = $amount;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a class="kuaijie" href="<?php echo C('WEB_URL');?>" val="<?php echo ($vo); ?>"><?php echo ($vo); ?>元</a><?php endforeach; endif; else: echo "" ;endif; ?>
           </td>
            <td align="center" style="color: #e60012;"></td></tr>
        <tr>
            <td class="title">存款时间：</td>
            <td>
                <input name="P_Time" id="P_Time" type="text" value="<?php echo date('Y-m-d H:i:s',time());?>" class="table-input" disabled=""></td>
            <td align="center">无需填写</td></tr>
        </tbody>
    </table>
        <div class="form-btn">
            <a href="###" id="querenzhifu" style="background-color: #c59503;">确认支付</a>
            <a href="<?php echo C('CALL_KF_URL');?>" target="_blank">联系客服</a>
            <a href="<?php echo C('HAPPY_URL');?>" target="_blank">进入游戏</a></div>
        <div style="height: 1px;clear: both;" id="payUrl" action="<?php echo U('Index/ordersubmit');?>"></div>
        <p class="tips"><span style="color:#f00;">温馨提示：</span>为了避免掉单情况的发生，请您在支付完成后，需等"支付成功"页面跳转出来, 再关闭页面，以免掉单！感谢配合！！！ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>支付成功后，若3分钟内未能及时到达您的会员账号请联系<a href="<?php echo C('CALL_KF_URL');?>" target="_self" textvalue="【在线客服】">【在线客服】</a>咨询； &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>祝您游戏愉快，盈利多多！O(∩_∩)O　　</p>          </div>
    <div class="copyright"><?php echo C('WEB_SITE_ICP');?></div></form>
    <div id="rencaijiekou">

    </div>
<script type="text/javascript" src="/Public/Home/js/app.js"></script>

</body></html>