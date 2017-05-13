<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($meta_title); ?>|OneThink管理平台</title>
    <link href="/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/common.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/module.css">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">
     <!--[if lt IE 9]>
    <script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/Public/static/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/jquery.mousewheel.js"></script>
    <!--<![endif]-->
    
</head>
<body>
    <!-- 头部 -->
    <div class="header">
        <!-- Logo -->
        <span class="logo"></span>
        <!-- /Logo -->

        <!-- 主导航 -->
        <ul class="main-nav">
            <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <!-- /主导航 -->

        <!-- 用户栏 -->
        <div class="user-bar">
            <a href="javascript:;" class="user-entrance"><i class="icon-user"></i></a>
            <ul class="nav-list user-menu hidden">
                <li class="manager">你好，<em title="<?php echo session('user_auth.username');?>"><?php echo session('user_auth.username');?></em></li>
                <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
                <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
                <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
            </ul>
        </div>
    </div>
    <!-- /头部 -->

    <!-- 边栏 -->
    <div class="sidebar">
        <!-- 子导航 -->
        
            <div id="subnav" class="subnav">
                <?php if(!empty($_extra_menu)): ?>
                    <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>
                <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
                    <?php if(!empty($sub_menu)): if(!empty($key)): ?><h3><i class="icon icon-unfold"></i><?php echo ($key); ?></h3><?php endif; ?>
                        <ul class="side-sub-menu">
                            <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
                                    <a class="item" href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul><?php endif; ?>
                    <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        
        <!-- /子导航 -->
    </div>
    <!-- /边栏 -->

    <!-- 内容区 -->
    <div id="main-content">
        <div id="top-alert" class="fixed alert alert-error" style="display: none;">
            <button class="close fixed" style="margin-top: 4px;">&times;</button>
            <div class="alert-content">这是内容</div>
        </div>
        <div id="main" class="main">
            
            <!-- nav -->
            <?php if(!empty($_show_nav)): ?><div class="breadcrumb">
                <span>您的位置:</span>
                <?php $i = '1'; ?>
                <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
                    <?php else: ?>
                    <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
                    <?php $i = $i+1; endforeach; endif; ?>
            </div><?php endif; ?>
            <!-- nav -->
            

            
    <style>
        .btn_active{
            background: #00A1CB;
        }
        .fl a:hover{
            background: #00A1CB;
        }
    </style>
    <!-- 标题 -->
    <div class="main-title">
        <h2>
            支付方式
        </h2>
    </div>

    <!-- 按钮工具栏 -->
    <div class="cf">
        <div class="fl">
            <a   id="startPay"  href="###"  class="btn">批量开启</a>
            <a    href="<?php echo U('Payment/sort');?>"  class="btn">排序</a>
            <a  href="<?php echo U('Payment/getFile');?>" class="btn">获取支付接口</a>

        </div>

        <!-- 高级搜索 -->

    </div>


    <!-- 数据表格 -->
    <div class="data-table">
        <table class="ztable">
            <thead>
            <tr>
                <th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
                <th class="">支付方式</th>
                <th class="">支付名称</th>
                <th class="">支付类型</th>
                <th class="">是否启用</th>
                <th class="">支付网址</th>

                <th class="">操作</th>
            </tr>
            </thead>
            <tbody>

            <?php if(is_array($payment)): $i = 0; $__LIST__ = $payment;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td><input class="ids" type="checkbox" name="ids[]" value="<?php echo ($vo["id"]); ?>" <?php if($vo["status"] == 'Y'): ?>checked<?php endif; ?> /></td>
                    <td><?php echo ($vo["method"]); ?> </td>
                    <td><?php echo ($vo["name"]); ?></td>
                    <td>
                        <?php switch($vo["paytype"]): case "0": ?><span style="color:green">微信</span><?php break;?>
                            <?php case "1": ?><span style="color:blue">支付宝</span><?php break;?>
                            <?php case "2": ?><span style="color:goldenrod">网银</span><?php break;?>
                            <?php case "3": ?><span style="color:green">平台微信</span><?php break;?>
                            <?php case "6": ?><span style="color:green">QQ</span><?php break;?>
                            <?php case "1": ?><span style="color:blue">平台支付宝</span><?php break;?>
                            <?php case "2": ?><span style="color:goldenrod">平台网银</span><?php break; endswitch;?>

                    </td>
                    <td>
                        <?php if($vo["status"] == 'Y'): ?><a href="<?php echo U('Payment/setCheckOne',array('id'=>$vo['id'],'status'=>'N'));?>" class="ajax-get confirm"> 启用</a>
                            <?php else: ?>
                            <a href="<?php echo U('Payment/setCheckOne',array('id'=>$vo['id'],'status'=>'Y'));?>" style="color:Red;" class="ajax-get confirm"> 禁用</a><?php endif; ?>

                    </td>
                    <td>
                        <?php echo ($vo["url"]); ?>

                    </td>

                    <td>
                        <a href="<?php echo U('Payment/edit',array('id'=>$vo['id']));?>">编辑</a>
                        <a href="<?php echo U('Payment/testPay',array('id'=>$vo['id']));?>" target="_blank">测试支付</a>

                    </td>



                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>


    </div>

    <!-- 分页 -->
    <div class="page">
        <?php echo ($order["_page"]); ?>
    </div>
    </div>


        </div>
        <div class="cont-ft">
            <div class="copyright">
                <div class="fl">感谢使用充值管理平台</div>
                <div class="fr">V<?php echo (ONETHINK_VERSION); ?></div>
            </div>
        </div>
    </div>
    <!-- /内容区 -->
    <script type="text/javascript">
    (function(){
        var ThinkPHP = window.Think = {
            "ROOT"   : "", //当前网站地址
            "APP"    : "/master.php?s=", //当前项目地址
            "PUBLIC" : "/Public", //项目公共目录地址
            "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
        }
    })();
    </script>
    <script type="text/javascript" src="/Public/static/think.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/common.js"></script>
    <script type="text/javascript" src="/Public/Home/js/layer/layer.js"></script>
    <script type="text/javascript">
        +function(){

            var getInfo=function(){
                layer.closeAll();
                url = "<?php echo U('Admin/getlistAction');?>";
                $.get(url,function(obj){

                    if(obj.error){
                        //边缘弹出
                        layer.open({
                            type: 1
                            ,offset: 'rb' //具体配置参考：offset参数项
                            ,content: '<div style="padding: 20px 40px;">'+obj.error+'</div>'
                            ,btn: '关闭全部'
                            ,btnAlign: 'c' //按钮居中
                            ,shade: 0 //不显示遮罩
                            ,yes: function(){
                                layer.closeAll();
                            }
                        });
                    }
                });
            }
            getInfo();
            setInterval(getInfo,30000);


            var $window = $(window), $subnav = $("#subnav"), url;
            $window.resize(function(){
                $("#main").css("min-height", $window.height() - 130);
            }).resize();

            /* 左边菜单高亮 */
            url = window.location.pathname + window.location.search;
            url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
            $subnav.find("a[href='" + url + "']").parent().addClass("current");

            /* 左边菜单显示收起 */
            $("#subnav").on("click", "h3", function(){
                var $this = $(this);
                $this.find(".icon").toggleClass("icon-fold");
                $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").
                      prev("h3").find("i").addClass("icon-fold").end().end().hide();
            });

            $("#subnav h3 a").click(function(e){e.stopPropagation()});

            /* 头部管理员菜单 */
            $(".user-bar").mouseenter(function(){
                var userMenu = $(this).children(".user-menu ");
                userMenu.removeClass("hidden");
                clearTimeout(userMenu.data("timeout"));
            }).mouseleave(function(){
                var userMenu = $(this).children(".user-menu");
                userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
                userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
            });

	        /* 表单获取焦点变色 */
	        $("form").on("focus", "input", function(){
		        $(this).addClass('focus');
	        }).on("blur","input",function(){
				        $(this).removeClass('focus');
			        });
		    $("form").on("focus", "textarea", function(){
			    $(this).closest('label').addClass('focus');
		    }).on("blur","textarea",function(){
			    $(this).closest('label').removeClass('focus');
		    });

            // 导航栏超出窗口高度后的模拟滚动条
            var sHeight = $(".sidebar").height();
            var subHeight  = $(".subnav").height();
            var diff = subHeight - sHeight; //250
            var sub = $(".subnav");
            if(diff > 0){
                $(window).mousewheel(function(event, delta){
                    if(delta>0){
                        if(parseInt(sub.css('marginTop'))>-10){
                            sub.css('marginTop','0px');
                        }else{
                            sub.css('marginTop','+='+10);
                        }
                    }else{
                        if(parseInt(sub.css('marginTop'))<'-'+(diff-10)){
                            sub.css('marginTop','-'+(diff-10));
                        }else{
                            sub.css('marginTop','-='+10);
                        }
                    }
                });
            }
        }();

    </script>
    

    <script type="text/javascript">
        $('#startPay').click(function(){
            var check = [];
            $('.ztable input:checkbox:checked').each(function(index,e){

                check.push($(this).val())
            });
            // check =$.serialize(check);
            console.log(check);
            if (check.length ==0){
                updateAlert('请勾选需要开启的支付方式');return false;
            };
            $.post("<?php echo U('Payment/setCheck');?>", { ids:check},
                    function(data){

                        if(data.status){
                            updateAlert(data.info,'alert-success');
                        }else{
                            updateAlert(data.info,'alert-error');
                        }
                        setTimeout(function(){
                            location.href="<?php echo U('Payment/index');?>";
                        },2000)

                    });
        });

        highlight_subnav('<?php echo U("Payment/index");?>');
    </script>

</body>
</html>