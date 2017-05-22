<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class KuaifuController extends HomeController {
    //系统首页
    public function wechatSubmit()
    {

        $this->xssRequest();
        echo '跳转中!请耐心等待...';
        $orderId = I('orderid');
        $tokenSign = I('tokenSign');
        if($tokenSign !== '8812662dcf3e5db0247c0f85909363fc'){

            $this->error('系统错误');
        }
        if($orderId){
            // 获取 订单
            $orderArr['id'] = $orderId;
            $order = D('Order')->info($orderArr);
            if(empty($order)){
                $this->error('订单异常!');
            }
            // 指定类型
            $type = '1004';

            $this->comm($order,$type);


        }else{
            $this->error('无效单号');
        }

    }

    public function  comm($order,$type){
        //获取 支付路径
        $payment  = D('Payment')->info(array('method'=>$order['pay_type']),'url,config');

        $config = unserialize($payment['config']);

        $parter = $config['parter'];
        $UserKey = $config['private'];


        // 请求数据赋值
        $data = "";
        // 商户APINMAE，WEB渠道一般支付
        $data['apiName'] = 'WEB_PAY_B2C';
        // 商户API版本
        $data['apiVersion'] = "1.0.0.0";
        // 商户在支付系统的平台号
        $data['platformID'] =   $parter;
        // 支付系统分配给商户的账号
        $data['merchNo'] = $parter;
        // 商户通知地址
        $data['merchUrl'] = 'http://'.$_SERVER['name'].'/index.php/Kuaifu/notify_confirm';
        // 银行代码，不传输此参数则跳转支付收银台，选择微信扫码直接跳转到微信付款界面,选择网银支付直接跳转到网银界面
        //商户订单号
        $data['bankCode'] = "";
        $data['orderNo'] = $order['id'];

        // 商户订单日期
        $data['tradeDate'] = date('Ymd',$order['addtime']);
        // 商户交易金额
        $data['amt'] = $order['amount'];
        // 商户参数
        $data['merchParam'] = $order['id'];
        // 商户交易摘要
        $data['tradeSummary'] = $order['id'];

        $data['choosePayType'] = $type;
        // 对含有中文的参数进行UTF-8编码
        // 将中文转换为UTF-8
        if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['merchUrl']))
        {
            $data['merchUrl'] = iconv("GBK","UTF-8", $data['merchUrl']);
        }

        if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['merchParam']))
        {

            $data['merchParam'] = iconv("GBK","UTF-8", $data['merchParam']);
        }

        if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['tradeSummary']))
        {
            $data['tradeSummary'] = iconv("GBK","UTF-8", $data['tradeSummary']);
        }
        $data['customerIP'] = $_SERVER['REMOTE_ADDR'];

        // 待加密字符串
        $str_to_sign = sprintf(
            "apiName=%s&apiVersion=%s&platformID=%s&merchNo=%s&orderNo=%s&tradeDate=%s&amt=%s&merchUrl=%s&merchParam=%s&tradeSummary=%s",
            $data['apiName'], $data['apiVersion'], $data['platformID'], $data['merchNo'], $data['orderNo'], $data['tradeDate'], $data['amt'], $data['merchUrl'], $data['merchParam'], $data['tradeSummary']
        );

        // 加密
        $sign  = MD5($data.$UserKey);
        $data['signMsg'] = $sign;

        $apiUrl  = 'http://pay888.hkkpay.com/cgi-bin/netpayment/pay_gate.cgi';

        $this->form($apiUrl,$data);
    }

    //系统首页
    public function aliSubmit()
    {
        $this->xssRequest();
        echo '跳转中!请耐心等待...';
        $orderId = I('orderid');
        $tokenSign = I('tokenSign');

        if($tokenSign !== '8812662dcf3e5db0247c0f85909363fc'){

            $this->error('系统错误');
        }
        if($orderId){
            // 获取 订单
            $orderArr['id'] = $orderId;
            $order = D('Order')->info($orderArr);
            if(empty($order)){
                $this->error('订单异常!');
            }
            $type = 992;
            $this->comm($order,$type);
        }else{
            $this->error('无效单号');
        }

    }

    //系统首页
    public function bankSubmit()
    {
        $this->xssRequest();
        echo '跳转中!请耐心等待...';
        $orderId = I('orderid');
        $tokenSign = I('tokenSign');

        if($tokenSign !== '8812662dcf3e5db0247c0f85909363fc'){

            $this->error('系统错误');
        }
        if($orderId){
            // 获取 订单
            $orderArr['id'] = $orderId;
            $order = D('Order')->info($orderArr);
            if(empty($order)){
                $this->error('订单异常!');
            }
            $type = 1006;
            $this->comm($order,$type);
        }else{
            $this->error('无效单号');
        }

    }


    /**
     * 支付回调
     */
    public function notify_confirm(){

        D('OrderCallback')->addData();
        //获取 支付路径
        $payment  = D('Payment')->info(array('method'=>'kuaifuwx'),'config');

        $config = unserialize($payment['config']);

        $tokenKey = $config['private'];

        $data['apiName'] = $_REQUEST["apiName"];
        // 通知时间
        $data['notifyTime'] = $_REQUEST["notifyTime"];
        // 支付金额(单位元，显示用)
        $data['tradeAmt'] = $_REQUEST["tradeAmt"];
        // 商户号
        $data['merchNo'] = $_REQUEST["merchNo"];
        // 商户参数，支付平台返回商户上传的参数，可以为空
        $data['merchParam'] = $_REQUEST["merchParam"];
        // 商户订单号
        $data['orderNo'] = $_REQUEST["orderNo"];
        // 商户订单日期
        $data['tradeDate'] = $_REQUEST["tradeDate"];
        // 支付系统订单号
        $data['accNo'] = $_REQUEST["accNo"];
        // 支付系统账务日期
        $data['accDate'] = $_REQUEST["accDate"];
        // 订单状态，0-未支付，1-支付成功，2-失败，4-部分退款，5-退款，9-退款处理中
        $data['orderStatus'] = $_REQUEST["orderStatus"];
        // 签名数据
        $data['signMsg'] = $_REQUEST["signMsg"];

        $result = sprintf(
            "apiName=%s&notifyTime=%s&tradeAmt=%s&merchNo=%s&merchParam=%s&orderNo=%s&tradeDate=%s&accNo=%s&accDate=%s&orderStatus=%s",
            $data['apiName'], $data['notifyTime'], $data['tradeAmt'], $data['merchNo'], $data['merchParam'], $data['orderNo'], $data['tradeDate'], $data['accNo'], $data['accDate'], $data['orderStatus']
        );
        $sign  = MD5($result.$tokenKey);
        if (strcasecmp($sign, $data['signMsg']) != 0) {

            die('验签失败');//此处作逻辑处理
        }else{

            $id = $data['orderNo'];

            $order = D('Order')->info(array('id'=>$id));
            if($order['amount'] != $data['tradeAmt']){
                D('Order')->updateData(array('id'=>$id),array('pay_status'=>'F'));
                exit('ok');
            }
            if($order['pay_status'] !='Y'){

                $sql = D('Order')->updateData(array('id'=>$id),array('pay_status'=>'Y'));

                if($sql){
                    exit('ok');

                }
            }else{
                echo 'failed';exit;
            }
        }

    }

    /**
     * @param
     * @param $apiUrl
     * @param $postData
     */
    public function  form($apiUrl,$postData) {

        $html  = '<form id="pay_form"  method="post" action="'.$apiUrl.'">';

        foreach($postData as $key=>$value) {
            $html .= '<input type="hidden" name="'.$key.'"  value="'.$value.'" />';
        }
        $html .='</form><script type="text/javascript">document.getElementById("pay_form").submit();</script>';
        echo $html;
    }

    public function createMethod(){

        $config = serialize(array('parter'=>'2106001494914638','private'=>'155241c36ccb424e69ed504c7476c4bb'));
        $paymentwx =  array(
            'method'=> 'kuaifuwx',
            'name'  => '快付微信',
            'config' => $config,
            'field' =>'商户号:parter,私钥:private',
            'status'=>'N',
            'url' =>'/index.php/Kuaifu/wechatSumbit',
            'paytype' => '0',
        );
        $paymentzfb =  array(
            'method'=> 'kuaifuzfb',
            'name'  => '快付支付宝',
            'config' => $config,
            'field' =>'商户号:parter,私钥:private',
            'status'=>'N',
            'url' =>'/index.php/Kuaifu/AliSumbit',
            'paytype' => '1',
        );

        $paymentbank =  array(
            'method'=> 'shunfuzfbWap',
            'name'  => '快付网银',
            'config' => $config,
            'field' =>'商户号:parter,私钥:private',
            'status'=>'N',
            'url' =>'/index.php/Kuaifu/bankSumbit',
            'paytype' => '2',
        );

        D('Payment')->add($paymentwx);
        D('Payment')->add($paymentzfb);

        D('Payment')->add($paymentbank);

    }



}