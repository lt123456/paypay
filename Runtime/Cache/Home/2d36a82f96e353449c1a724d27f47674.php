<?php if (!defined('THINK_PATH')) exit();?><!Doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta content="telephone=no" name="format-detection">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=2.0">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <title><?php echo C('WEB_SITE_TITLE');?></title>
    <meta name="keywords" content="<?php echo C('WEB_SITE_KEYWORD');?>">
    <meta name="description" content="<?php echo c('WEB_SITE_DESCRIPTION');?>">
    <link rel="stylesheet" href="/Public/Home/css/mobile.css">
    <script type="text/javascript" src="/Public/Home/js/jquery.js"></script>
    <script type="text/javascript" src="/Public/Home/js/swipe.tryine.min.js"></script>
    <script type="text/javascript" src="/Public/Home/js/channel.min.js"></script>
    <script type="text/javascript" src="/Public/Home/js/demo.js"></script>
    <script type="text/javascript" src="/Public/Home/js/layer/layer.js"></script>
    <script>
        $(document).ready(function(){
            $(".scrollleft").imgscroll({
                speed: 40,    //图片滚动速度
                amount: 0,    //图片滚动过渡时间
                width: 1,     //图片滚动步数
                dir: "left"   // "left" 或 "up" 向左或向上滚动
            });
        });
        var zuidi = 10;
        var zuida = 3000;
    </script>
</head>

<body>
<form method="post" id="pay" >
    <input type="hidden" name="wether" value="Zxcvbnm123456">
    <div class="header" style="background-color: #333;"><img src="/Public/Home/images/mobile/logo.png" alt=""></div>
    <!--焦点图 -->
    <div id="j_imgSwipe" class="swipe" style="visibility: visible;">
        <div class="swipe-wrap" data-sudaclick="imgswipe" style="width: 1125.6px;">
            <div class="swipe_pic" data-index="0" style="width: 375.2px; left: 0px; transition-duration: 300ms; transform: translate(0px, 0px) translateZ(0px);"><a href="<?php echo C('WEB_URL');?>"><img src="/Public/Home/images/mobile/banner1.jpg" alt="banner"> </a></div>
            <div class="swipe_pic" data-index="1" style="width: 375.2px; left: -375.2px; transition-duration: 0ms; transform: translate(375.2px, 0px) translateZ(0px);"><a href="<?php echo C('WEB_URL');?>"><img src="/Public/Home/images/mobile/banner2.jpg" alt="banner"> </a></div>
            <div class="swipe_pic" data-index="2" style="width: 375.2px; left: -750.4px; transition-duration: 300ms; transform: translate(-375.2px, 0px) translateZ(0px);"><a href="<?php echo C('WEB_URL');?>"><img src="/Public/Home/images/mobile/banner3.jpg" alt="banner"> </a></div>
        </div>
        <ul class="swipe_num">
            <li class="active"></li>
            <li class=""></li>
            <li class=""></li>
        </ul>
    </div>
    <!--焦点图 -->
    <!--滚动条-->
    <div class="td_f">
        <p class="so_p"><img src="/Public/Home/images/mobile/pic_1.jpg" alt=""></p>
        <a href="<?php echo C('WEB_URL');?>" title="">
            <div class="scrollleft" style="overflow: hidden; position: relative;">
                <ul style="margin: 0px; padding: 0px; overflow: hidden; position: relative; list-style: none; width: 858px; left: -268px;"><li style="position: relative; overflow: hidden; float: left;">
                    将产生的二维码保存至相册,再识别相册二维码.                    </li><li style="position: relative; overflow: hidden; float: left;">
                    将产生的二维码保存至相册,再识别相册二维码.                    </li><li style="position: relative; overflow: hidden; float: left;">
                    将产生的二维码保存至相册,再识别相册二维码.                    </li></ul>
            </div>
        </a>
    </div>
    <!--滚动条-->
    <div style="height: 7px;background-color: #eee; display: flex; "></div>

    <input name="client_type" id="client_type" type="hidden" value="2">
    <div class="m_at">
        <span class="title">会员账号:</span>
        <div class="content">
            <input class="select_ipt" value="<?php echo $_COOKIE['onethink_home_username'];?>" name="username" id="username" placeholder="请填写快速充值中心游戏账户（*）" onpaste="return false" onkeyup="value=value.replace(/[^A-Z\a-\z0-9\_]/g,&#39;&#39;)" type="text">
        </div>
    </div>
    <div class="m_at">
        <span class="title">确认账号:</span>
        <div class="content">
            <input class="select_ipt" value="<?php echo $_COOKIE['onethink_home_username'];?>" name="re_username" id="rusername" placeholder="请确认游戏账户，否则无法正常充值（*）" onpaste="return false" onkeyup="value=this.value.replace(/[^A-Z\a-\z0-9\_]+/g,&#39;&#39;)" type="text">
        </div>
    </div>
    <div class="m_at">
        <span class="title">存款额度:</span>
        <div class="content">
            <input class="select_ipt" name="order_amount" id="coin" placeholder="10-3000" onkeyup="value=this.value.replace(/\D+/g,&#39;&#39;)" type="text">
            <p>
                <font color="red">温馨提醒:</font>入款时请不要充值整数，例如：501、699等不为整数的金额可提高充值成功率！
            </p>
        </div>
    </div>
    <div class="m_at">
        <?php
 $amount = explode(',',C('RANDOM_AMOUNT')); if(!is_array($amount)){ $amount = array('202','507','898','1024','1588','2043','2089'); } ?>
        <?php if(is_array($amount)): $i = 0; $__LIST__ = $amount;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a class="kuaijie" href="<?php echo C('WEB_URL');?>" val="<?php echo ($vo); ?>"><?php echo ($vo); ?>元</a><?php endforeach; endif; else: echo "" ;endif; ?>

    </div>
    <div class="m_at">
        <span class="title">存款时间:</span>
        <div class="content">
            <input type="text" value=" <?php echo date('Y-m-d H:i:s',time());?>" class="select_ipt" style="color: #333;" name="P_Time" id="P_Time" disabled="">
        </div>
    </div>

    <div style="height: 7px;background-color: #eee; display: flex;"></div>
    <div id="getNewBackgroup" class="m_at">

        <?php if(is_array($payments)): $i = 0; $__LIST__ = $payments;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="even_pay">
                <?php switch($vo["paytype"]): case "0": ?><span class="title" style="text-align: center;">
                                <img style="height: 40px;" src="/Public/Home/images/mobile/wx.jpg" alt="">
                          </span>
                        <div class="content">
                         <span>微信扫码</span><br>
                            <em style="color: #999;">
                                推荐微信扫码用户使用
                            </em>
                            <div class="even_select">
                                <input type="radio" name="pay_type"  SB_type="WEIXIN" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="<?php echo ($vo["id"]); ?>">
                                <label class="pay-label" for="WEIXIN"></label>
                            </div>
                        </div><?php break;?>
                    <?php case "1": ?><span class="title" style="text-align: center;">
                                <img style="height: 40px;" src="/Public/Home/images/mobile/alipay.jpg" alt="">
                          </span>
                        <div class="content">
                            <span>支付宝扫码</span><br>
                            <em style="color: #999;">
                                推荐支付宝扫码用户使用
                            </em>
                            <div class="even_select">
                                <input type="radio" name="pay_type"  SB_type="AliPay" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="<?php echo ($vo["id"]); ?>">
                                <label class="pay-label" for="AliPay"></label>
                            </div>
                        </div><?php break;?>
                    <?php case "2": ?><span class="title" style="text-align: center;">
                                <img style="height: 40px;" src="/Public/Home/images/mobile/bank.jpg" alt="">
                          </span>
                        <div class="content">
                            <span>网银</span><br>
                            <em style="color: #999;">
                                推荐网银用户使用
                            </em>
                            <div class="even_select">
                                <input type="radio" name="pay_type"  SB_type="Bank" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="<?php echo ($vo["id"]); ?>">
                                <label class="pay-label" for="AliPay"></label>
                            </div>
                        </div><?php break;?>
                    <?php case "3": ?><span class="title" style="text-align: center;">
                                <img style="height: 40px;" src="/Public/Home/images/mobile/wx.jpg" alt="">
                          </span>
                        <div class="content">
                            <span>平台微信扫码</span><br>
                            <em style="color: #999;">
                                推荐微信扫码用户使用
                            </em>
                            <div class="even_select">
                                <input type="radio" name="pay_type"  SB_type="WEIXIN" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="<?php echo ($vo["id"]); ?>">
                                <label class="pay-label" for="WEIXIN"></label>
                            </div>
                        </div><?php break;?>
                    <?php case "4": ?><span class="title" style="text-align: center;">
                                <img style="height: 40px;" src="/Public/Home/images/mobile/alipy.jpg" alt="">
                          </span>
                        <div class="content">
                            <span>平台支付宝扫码</span><br>
                            <em style="color: #999;">
                                推荐支付宝扫码用户使用
                            </em>
                            <div class="even_select">
                                <input type="radio" name="pay_type"  SB_type="AliPay" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="<?php echo ($vo["id"]); ?>">
                                <label class="pay-label" for="AliPay"></label>
                            </div>
                        </div><?php break;?>
                    <?php case "5": ?><span class="title" style="text-align: center;">
                                <img style="height: 40px;" src="/Public/Home/images/mobile/bank.jpg" alt="">
                          </span>
                        <div class="content">
                            <span>平台网银</span><br>
                            <em style="color: #999;">
                                推荐网银用户使用
                            </em>
                            <div class="even_select">
                                <input type="radio" name="pay_type"  SB_type="Bank" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="<?php echo ($vo["id"]); ?>">
                                <label class="pay-label" for="AliPay"></label>
                            </div>
                        </div><?php break;?>
                    <?php case "6": ?><span class="title" style="text-align: center;">
                                <img style="height: 40px;" src="/Public/Home/images/mobile/qq.png" alt="">
                          </span>
                        <div class="content">
                            <span>QQ扫码</span><br>
                            <em style="color: #999;">
                                推荐QQ扫码用户使用
                            </em>
                            <div class="even_select">
                                <input type="radio" name="pay_type"  SB_type="QQ" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="<?php echo ($vo["id"]); ?>">
                                <label class="pay-label" for="AliPay"></label>
                            </div>
                        </div><?php break;?>
                    <?php case "100": ?><span class="title" style="text-align: center;">
                                <img style="height: 40px;" src="/Public/Home/images/mobile/wx.jpg" alt="">
                          </span>
                        <div class="content">
                            <span>微信Wap</span><br>
                            <em style="color: #999;">
                                推荐微信用户使用
                            </em>
                            <div class="even_select">
                                <input type="radio" name="pay_type"  SB_type="WEIXIN" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="<?php echo ($vo["id"]); ?>">
                                <label class="pay-label" for="WEIXIN"></label>
                            </div>
                        </div><?php break;?>
                    <?php case "101": ?><span class="title" style="text-align: center;">
                                <img style="height: 40px;" src="/Public/Home/images/mobile/alipy.jpg" alt="">
                          </span>
                        <div class="content">
                            <span>支付宝Wap</span><br>
                            <em style="color: #999;">
                                推荐支付宝扫码用户使用
                            </em>
                            <div class="even_select">
                                <input type="radio" name="pay_type"  SB_type="AliPay" class="regular-radio" read="<?php echo ($vo["method"]); ?>" value="<?php echo ($vo["id"]); ?>">
                                <label class="pay-label" for="AliPay"></label>
                            </div>
                        </div><?php break; endswitch;?>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
     </div>


    <div class="m_at" id="zhifubaozhanghao" style="display: none;">
        <span class="title" style="font-size: 13px;">支付宝昵称:</span>
        <div class="content">
            <input class="oclass select_ipt" value="" placeholder="请认真填写支付宝昵称，否则财务无法第一时间入款(*）" onpaste="return false" type="text">
            <br>
            <p>
                存款时备注请填写会员帐号
            </p>
        </div>
    </div>

    <div class="m_at" id="weixinzhanghao" style="display: none;">
        <span class="title" style="font-size: 13px;">微信昵称:</span>
        <div class="content">
            <input class="oclass select_ipt" value="" placeholder="请认真填写微信昵称，否则财务无法第一时间入款(*）" onpaste="return false" type="text">
            <br>
            <p>
                存款时备注请填写会员帐号
            </p>
        </div>
    </div>

    <div class="m_at" id="qqzhanghao" style="display: none;">
        <span class="title" style="font-size: 13px;">QQ账号:</span>
        <div class="content">
            <input class="oclass select_ipt" value="" placeholder="请认真填写QQ账号，否则财务无法第一时间入款(*）" onpaste="return false" type="text">
            <br>
            <p>
                存款时备注请填写会员帐号
            </p>
        </div>
    </div>

    <div style="height: 7px;background-color: #eee; display: flex;"></div>

    <div class="m_at"><a class="even_button" href="<?php echo C(WEB_URL);?>" id="querenzhifu">确认支付</a></div>

    <div style="height: 7px;background-color: #eee; display: flex;"></div>
    <div class="sp_n">
        <span style="font-size: 18px;color: #403a38; display: inline-block;text-align: center; width: 100%;">温馨提示</span>
        <p><span style="margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);">1、入款即送0.5%存款优惠，次次存，次次送！支付宝单笔限制5000元以下，微信支付单笔限制5000元以下。</span><br style="margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);"><span style="margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);">2、为了避免掉单情况的发生，请您在支付完成后，等待"支付成功"页面跳转出来后再关闭页面，以免掉单。</span><br style="margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);"><span style="margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);">3、支付未到账？无法支付？请联系<a href="https://kf1.learnsaas.com/chat/chatClient/chatbox.jsp?companyID=721812&amp;configID=57826&amp;jid=5882958245&amp;s=1" target="_blank">【在线客服】</a></span></p>        </div>
    <div style="height: 7px;background-color: #eee; display: flex;"></div>
    <div class="foot"><?php echo C('WEB_SITE_ICP');?></div>
        <div style="height: 1px;clear: both;" id="payUrl" action="<?php echo U('Index/ordersubmit');?>"></div>
</form>

<div id="rencaijiekou">

</div>
<script type="text/javascript" src="/Public/Home/js/app.js"></script>

</body></html>