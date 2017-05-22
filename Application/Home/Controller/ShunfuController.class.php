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
class  ShunfuController extends HomeController {

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

        $value   = $order['amount'];
        $orderid = $order['id'];

        //地址
        $apiurl = 'http://interface.shunfoo.com/Bank/';

        $callbackurl = 'http://'.$_SERVER['SERVER_NAME'].'/index.php/Shunfu/notify_confirm';
        $hrefbackurl = 'http://'.$_SERVER['SERVER_NAME'].'/index.php/Order/success';
        $payerIp  = $_SERVER['REMOTE_ADDR'];

        $signText = 'parter='.$parter.'&type='.$type.'&value='.$value.'&orderid='.$orderid.'&callbackurl='.$callbackurl.$UserKey;

        $signText = iconv('UTF-8','gb2312',$signText);

        $signValue = strtolower(md5($signText));

        $postData = array(
            'parter' => $parter,
            'type'=> $type,
            'value'=> $value,
            'orderid'=>$orderid,
            'callbackurl'=>$callbackurl,
            'hrefbackurl'=> $hrefbackurl,
            'payerIp'=>$payerIp,
            'sign'=>$signValue,
        );
        $this->form($apiurl,$postData);
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
    public function wechatWapSubmit()
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
            $type = 1007;
            $this->comm($order,$type);
        }else{
            $this->error('无效单号');
        }

    }

        //系统首页
        public function aliWapSubmit()
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
        $payment  = D('Payment')->info(array('method'=>'shunfuwx'),'config');

        $config = unserialize($payment['config']);


        $tokenKey = $config['private'];

        $orderid = $_REQUEST['orderid'];
        $opstate = $_REQUEST['opstate'];
        $ovalue  =$_REQUEST['ovalue'];

        $sign = $_REQUEST['sign'];

        $signText = 'orderid='.$orderid.'&opstate='.$opstate.'&ovalue='.$ovalue.$tokenKey;
        $signText  = iconv('utf-8','gb2312',$signText);
        $signValue = strtolower(md5($signText));
        if ($signValue != $sign)//签名正确
        {
            die('验签失败');//此处作逻辑处理
        }else{

            $id =$orderid;

            $order = D('Order')->info(array('id'=>$id));
            if($order['amount'] != $ovalue){
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
        $paymentwx =  array(
            'method'=> 'shunfuwx',
            'name'  => '顺付微信',
            'config' => serialize(array('parter'=>'1935','private'=>'155e21af47724b1fa6c33edc4ad9f86c')),
            'field' =>'商户号:parter,私钥:private',
            'status'=>'N',
            'url' =>'/index.php/Shunfu/wechatSumbit',
            'paytype' => '0',
        );
        $paymentzfb =  array(
            'method'=> 'shunfuzfb',
            'name'  => '顺付支付宝',
            'config' => serialize(array('parter'=>'1935','private'=>'155e21af47724b1fa6c33edc4ad9f86c')),
            'field' =>'商户号:parter,私钥:private',
            'status'=>'N',
            'url' =>'/index.php/Shunfu/AliSumbit',
            'paytype' => '1',
        );
        $paymentwxwap =  array(
            'method'=> 'shunfuwxWap',
            'name'  => '顺付微信wAP',
            'config' => serialize(array('parter'=>'1935','private'=>'155e21af47724b1fa6c33edc4ad9f86c')),
            'field' =>'商户号:parter,私钥:private',
            'status'=>'N',
            'url' =>'/index.php/Shunfu/wechatWapSumbit',
            'paytype' => '100',
        );
        $paymentzfbwap =  array(
            'method'=> 'shunfuzfbWap',
            'name'  => '顺付支付宝wAP',
            'config' => serialize(array('parter'=>'1935','private'=>'155e21af47724b1fa6c33edc4ad9f86c')),
            'field' =>'商户号:parter,私钥:private',
            'status'=>'N',
            'url' =>'/index.php/Shunfu/aliWapSumbit',
            'paytype' => '101',
        );

         D('Payment')->add($paymentwx);
         D('Payment')->add($paymentzfb);
         D('Payment')->add($paymentwxwap);
         D('Payment')->add($paymentzfbwap);

    }



}
