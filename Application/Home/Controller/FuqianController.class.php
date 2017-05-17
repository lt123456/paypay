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
class  FuqianController extends HomeController {

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
            $btype = '83';

            $this->comm($order,$btype);


        }else{
            $this->error('无效单号');
        }

    }

    public function  comm($order,$btype){
        //获取 支付路径
        $payment  = D('Payment')->info(array('method'=>$order['pay_type']),'url,config');

        $config = unserialize($payment['config']);

        $UserID = $config['parter'];
        $UserKey = $config['private'];

        $value  = $order['amount'];
        $orderid = $order['id'];

        //地址
        $apiurl = 'http://api.xiaacd.top/api/bapi.aspx';

        $ptype =0;
        $notifyurl = U('Fuqian/notify_confirm');
        $returlurl = 'http://'.$_SERVER['SERVER_NAME'].'/index.php/Order/success';
        $signText = 'userid'.$UserID.'orderid'.$orderid.'btype'.$btype.'ptype'.$ptype.'value'.$value.'notifyurl'.$notifyurl.'returnurl'.$returlurl.$UserKey;
        $signValue = strtolower(md5($signText));

        $postData = array(
            'userid'=>$UserID,
            'orderid'=>$orderid,
            'btype'=>$btype,
            'ptype'=>$ptype,
            'value'=>$value,
            'notifyurl'=>$notifyurl,
            'returnurl'=>$returlurl,
            'sign'=>$signValue,
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
            $btype = 84;
            $this->comm($order,$btype);
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
        $payment  = D('Payment')->info(array('method'=>'fuqianwx'),'config');

        $config = unserialize($payment['config']);


        $tokenKey = $config['private'];

        $userid = trim($_REQUEST['userid']);
        $orderid = trim($_REQUEST['orderid']);
        $btype = trim($_REQUEST['btype']);
        $result = trim($_REQUEST['result']);
        $value = trim($_REQUEST['value']);
        $realvalue = trim($_REQUEST['realvalue']);
        $sign = trim($_REQUEST['sign']);
        $signText = 'userid'.$userid.'orderid'.$orderid.'btype'.$btype.'result'.$result.'value'.$value.'realvalue'.$realvalue.$tokenKey;

        $signValue = strtolower(md5($signText));
        if ($signValue != $sign)//签名正确
        {
            die('验签失败');//此处作逻辑处理
        }else{

            $id =$orderid;

            $order = D('Order')->info(array('id'=>$id));
            if($order['amount'] != $value){
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


}