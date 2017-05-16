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
class  JuyuanController extends HomeController {

    //系统首页
    public function wechatSumbit()
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
            $banktype = 'WEIXIN';

            $this->comm($order,$banktype);


        }else{
            $this->error('无效单号');
        }

    }

    public function  comm($order,$banktype){
        //获取 支付路径
        $payment  = D('Payment')->info(array('method'=>$order['pay_type']),'url,config');

        $config = unserialize($payment['config']);

        $partner = $config['parter'];
        $key = $config['private'];

        $paymoney  = $order['amount'];
        $ordernumber = $order['id'];


        $attach = 'PAY';
        $apiurl = "http://pay.juypay.com/PayBank.aspx";
        $callbackurl = U('Juyuan/notify_confirm');
        $hrefbackurl = U('Order/success');
        $signSource = sprintf("partner=%s&banktype=%s&paymoney=%s&ordernumber=%s&callbackurl=%s%s", $partner, $banktype, $paymoney, $ordernumber, $callbackurl, $key);
        $sign = md5($signSource);//32位小写MD5签名值，UTF-8编码

        $postData = array(
            'banktype'=>$banktype,
            'partner'=>$partner,
            'paymoney'=>$paymoney,
            'ordernumber'=>$ordernumber,
            'callbackurl'=>$callbackurl,
            'hrefbackurl'=>$hrefbackurl,
            'attach' =>$attach,
            'sign'=>$sign
        );
        $this->form($apiurl,$postData);
    }

    //系统首页
    public function AliSubmit()
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
            $banktype = 'ALIPAYSCAN';
            $this->comm($order,$banktype);
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
        $payment  = D('Payment')->info(array('method'=>'juyuanwx'),'config');

        $config = unserialize($payment['config']);

        $partner = $config['parter'];
        $tokenKey = $config['private'];

        $orderstatus = $_GET["orderstatus"];
        $ordernumber = $_GET["ordernumber"];
        $paymoney = $_GET["paymoney"];
        $sign = $_GET["sign"];
        $attach = $_GET["attach"];
        $signSource = sprintf("partner=%s&ordernumber=%s&orderstatus=%s&paymoney=%s%s", $partner, $ordernumber, $orderstatus, $paymoney,$tokenKey);
        if ($sign != md5($signSource))//签名正确
        {
            die('验签失败');//此处作逻辑处理
        }

        if($orderstatus =='1'){

            $id =$ordernumber;

            $order = D('Order')->info(array('id'=>$id));
            if($order['amount'] != $paymoney){
                D('Order')->updateData(array('id'=>$id),array('pay_status'=>'F'));
               exit('ok');
            }
            if($order['pay_status'] !='Y'){

                $sql = D('Order')->updateData(array('id'=>$id),array('pay_status'=>'Y'));

                if($sql){
                    echo 'ok';exit;
                }
            }else{
                echo 'failed';exit;
            }
        }else{
            echo 'failed';exit;
        }

    }

    /**
     * @param
     * @param $apiUrl
     * @param $postData
     */
    public function  form($apiUrl,$postData) {

         $html  = '<form id="pay_form" method="post" action="'.$apiUrl.'">';

        foreach($postData as $key=>$value) {
              $html .= '<input type="hidden" name="'.$key.'"  value="'.$value.'" />';
        }
        $html .='</form><script type="text/javascript">document.getElementById("pay_form").submit();</script>';
        echo $html;
    }


}