$('.kuaijie').click( function()
{
   $(this).siblings().css('color','#00BCD4').css('background-color','#fff');
    var amount = $(this).attr('val');
    $('input[name="order_amount"]').val( amount );
    $(this).css('color','#fff').css('background-color','#2196F3');
    return false;
});
$('.even_pay').click( function()
{
    $(this).find('input').attr('checked', true);
    $("#bank_code").val( $("input[type=radio]:checked").attr('btype') );
});



$(".oclass").change(function() {
    $("#other").val($(this).val());
});


$(".pay-label").live('click',function() {

    $(this).siblings('input').attr('checked', true);

    $("#bank_code").val( $("input[type=radio]:checked").attr('btype') );

    if ($("input[type=radio]:checked").attr('ctrname') == 'Gerenzhifubao') {
        $("#zhifubaozhanghao").show();
    } else {
        $("#zhifubaozhanghao").hide();
        $("#other").val('')
    }
    if ($("input[type=radio]:checked").attr('ctrname') == 'Gerenweixin') {
        $("#weixinzhanghao").show();
    } else {
        $("#weixinzhanghao").hide();
        $("#other").val('')
    }
    if ($("input[type=radio]:checked").attr('ctrname') == 'Gerenqq') {
        $("#qqzhanghao").show();
    } else {
        $("#qqzhanghao").hide();
        $("#other").val('')
    }
});


$(".pay-label label").live('click',function() {

    $("#bank_code").val( $("input[type=radio]:checked").attr('btype') );


    $(this).siblings('input').attr('checked', true);
    if ($("input[type=radio]:checked").attr('ctrname') == 'Gerenzhifubao') {
        $("#zhifubaozhanghao").show();
    } else {
        $("#zhifubaozhanghao").hide();
        $("#other").val('')
    }
    if ($("input[type=radio]:checked").attr('ctrname') == 'Gerenweixin') {
        $("#weixinzhanghao").show();
    } else {
        $("#weixinzhanghao").hide();
        $("#other").val('')
    }

    if ($("input[type=radio]:checked").attr('ctrname') == 'Gerenqq') {
        $("#qqzhanghao").show();
    } else {
        $("#qqzhanghao").hide();
        $("#other").val('')
    }
});


// 支付
$("#querenzhifu").click( function()
{


	var username = $("#username").val();
    var coin = $("#coin").val();

    var bankco = $("input[type=radio]:checked").val();
    var rusername = $("#rusername").val();

    if (username == null || username == "") {
        layer.alert('[提示]用户名不能为空！', {
            skin: 'layui-layer-molv' //样式类名
            ,closeBtn: 0
            ,time:2000
        });
        return false;
    }
    if (rusername == null || rusername == "" || rusername != username) {
        layer.alert('[提示]2次用户名输入不一致!', {
            skin: 'layui-layer-molv' //样式类名
            ,closeBtn: 0
            ,time:2000
        });
        return false;
    }

    if (isNaN(coin)) {
        layer.alert('[提示]存款额度非有效数字！', {
            skin: 'layui-layer-molv' //样式类名
            ,closeBtn: 0
            ,time:2000
        });

        return false;
    }
    if (coin < zuidi || coin == '') {
        layer.alert("[提示]"+zuidi+"元以上或者"+zuidi+"元才能存款！", {
            skin: 'layui-layer-molv' //样式类名
            ,closeBtn: 0
            ,time:2000
        });

        return false;
    }


    if (coin > zuida) {
        layer.alert("[提示]存款金额不能超过"+zuida+"！", {
            skin: 'layui-layer-molv' //样式类名
            ,closeBtn: 0
            ,time:2000
        });

        return false;
    }
    if (bankco == null || bankco == "") {
        layer.alert("[提示]支付银行不能为空！", {
            skin: 'layui-layer-molv' //样式类名
            ,closeBtn: 0
            ,time:2000
        });

        return false;
    }

    //if ($("#zhifubaozhanghao").css('display') != 'none') {
    //    if ($("#zhifubaozhanghao").find('input').val() == '') {
    //        alert('[提示]支付宝昵称不能为空!');
    //        return false;
    //    }
    //}
    //
    //if ($("#weixinzhanghao").css('display') != 'none') {
    //    if ($("#weixinzhanghao").find('input').val() == '') {
    //        alert('[提示]微信昵称不能为空!');
    //        return false;
    //    }
    //}
    //
    //if ($("#qqzhanghao").css('display') != 'none') {
    //    if ($("#qqzhanghao").find('input').val() == '') {
    //        alert('[提示]QQ账号不能为空!');
    //        return false;
    //    }
    //}

     var data = $("#pay").serialize();
     pay_type = $("input[type=radio]:checked").attr('read');

    data += '&pay_type='+pay_type;



    var act = $("#payUrl").attr('action');
    var load =layer.load(0,{shade: [0.5,'#000']});
    $.post(act, data,
    function(res) {
        layer.close(load);
        if (res.status == 1) {

            $('#rencaijiekou').html('');
            var html  ='<form method="post" action="'+res.url+'" id="submitOrder"><input type="hidden" name="orderId" value="'+res.orderId+'"></form>';

            $('#rencaijiekou').html(html);

            $('#submitOrder').submit();

        }else{
            layer.alert(res.info, {
                skin: 'layui-layer-molv' //样式类名
                ,closeBtn: 0
                ,time:2000
            });
        }
});
    return false;
});

if ( window != top )
{
    top.location.href = location.href;
}


if( ! window.navigator.cookieEnabled){
   alert("浏览器配置错误，Cookie不可用！请开启Cookie以便带来更好的体验效果");  
}

// 关闭弹窗
$("#tips-close").click(function() {
    $(".tips-plane").hide();
});
var i = 10;
setInterval(function() {
    if (i > 0) {
        i--;
    } else {
        $("#tips-close").click();
    }
    $("#paytime").text(i);
},
1000);