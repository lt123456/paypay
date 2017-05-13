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
class  DingpayController extends HomeController {

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
            //获取 支付路径
            $payment  = D('Payment')->info(array('method'=>$order['pay_type']),'url,config');

            $config = unserialize($payment['config']);

            $priKey = $config ['private_key'];

            $noid = $config ['noid'];

            $priKey = openssl_get_privatekey ( $priKey );

            $merchant_code = $noid;
//wxpay
            $service_type = "wxpay";

            $notify_url = U('DingPay/notify_confirm');

            $interface_version = "V3.0";

            $sign_type = "RSA-S";

            $order_no = $order ['id'];

            $order_time = date ( "Y-m-d H:i:s", $order ['addtime'] );

            $order_amount = $order ['amount'];

            $product_name = "在线充值";

            $product_code = "";

            $product_num = "";

            $product_desc = "";

            $extra_return_param = "";

            $extend_param = "";

            $signStr = "";

            if ($extend_param != "") {
                $signStr = $signStr . "extend_param=" . $extend_param . "&";
            }

            if ($extra_return_param != "") {
                $signStr = $signStr . "extra_return_param=" . $extra_return_param . "&";
            }

            $signStr = $signStr . "interface_version=" . $interface_version . "&";

            $signStr = $signStr . "merchant_code=" . $merchant_code . "&";

            $signStr = $signStr . "notify_url=" . $notify_url . "&";

            $signStr = $signStr . "order_amount=" . $order_amount . "&";

            $signStr = $signStr . "order_no=" . $order_no . "&";

            $signStr = $signStr . "order_time=" . $order_time . "&";

            if ($product_code != "") {
                $signStr = $signStr . "product_code=" . $product_code . "&";
            }

            if ($product_desc != "") {
                $signStr = $signStr . "product_desc=" . $product_desc . "&";
            }

            $signStr = $signStr . "product_name=" . $product_name . "&";

            if ($product_num != "") {
                $signStr = $signStr . "product_num=" . $product_num . "&";
            }

            $signStr = $signStr . "service_type=" . $service_type;

            openssl_sign ( $signStr, $sign_info, $priKey, OPENSSL_ALGO_MD5 );

            $sign = urlencode ( base64_encode ( $sign_info ) );

            $postdata = "";

            if ($extend_param != "") {
                $postdata = $postdata . 'extend_param=' . $extend_param . "&";
            }

            if ($extra_return_param != "") {
                $postdata = $postdata . 'extra_return_param=' . $extra_return_param . "&";
            }

            if ($product_code != "") {
                $postdata = $postdata . 'product_code=' . $product_code . "&";
            }

            if ($product_desc != "") {
                $postdata = $postdata . 'product_desc=' . $product_desc . "&";
            }

            if ($product_num != "") {
                $postdata = $postdata . 'product_num=' . $product_num . "&";
            }
            $postdata = $postdata . 'merchant_code=' . $merchant_code . "&";

            $postdata = $postdata . 'service_type=' . $service_type . "&";

            $postdata = $postdata . 'notify_url=' . $notify_url . "&";

            $postdata = $postdata . 'interface_version=' . $interface_version . "&";

            $postdata = $postdata . 'sign_type=' . $sign_type . "&";

            $postdata = $postdata . 'sign=' . $sign . "&";

            $postdata = $postdata . 'order_no=' . $order_no . "&";

            $postdata = $postdata . 'order_time=' . $order_time . "&";

            $postdata = $postdata . 'order_amount=' . $order_amount . "&";

            $postdata = $postdata . 'product_name=' . $product_name;

//echo "发送到智付的数据为：" . "<br>" . $postdata . "<br>";die;

            $ch = curl_init ();
//

            curl_setopt ( $ch, CURLOPT_URL, "https://api.dinpay.com/gateway/api/weixin" );
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
            curl_setopt ( $ch, CURLOPT_POST, true );
            curl_setopt ( $ch, CURLOPT_HEADER, false );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postdata );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
            $response = curl_exec ( $ch );
            $res = simplexml_load_string ( $response );
            curl_close ( $ch );

            $resp_code = $res->response->resp_code;

            if($resp_code=="SUCCESS"){

                $qrcode=$res->response->trade->qrcode;

                $this->display();

            }else{

                 $this->error($res->response->resp_desc);
            }

        }else{
            $this->error('无效单号');
        }

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
            //获取 支付路径
            $payment  = D('Payment')->info(array('method'=>$order['pay_type']),'url,config');

            $config = unserialize($payment['config']);

            $priKey = $config ['private_key'];

            $noid = $config ['noid'];

            $priKey = openssl_get_privatekey ( $priKey );
            $merchant_code = $noid;
//wxpay
            $service_type = "direct_pay";

            $interface_version = "V3.0";

            $sign_type = "RSA-S";

            $input_charset = "UTF-8";

            $notify_url = U('Dingpay/notify_confirm');

            $order_no = $order ['id'];

            $order_time = date ( "Y-m-d H:i:s", $order ['addtime'] );

            $order_amount = $order['amount'];

            $product_name = "amount";

//以下参数为可选参数，如有需要，可参考文档设定参数值

            $return_url = U('Order/success');

            $pay_type = "";

            $redo_flag = "";

            $product_code = "";

            $product_desc = "";

            $product_num = "";

            $show_url = "";

            $client_ip ="" ;

            $bank_code = "";

            $extend_param = "";

            $extra_return_param = "";




/////////////////////////////   参数组装  /////////////////////////////////
            /**
            除了sign_type参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
             */

            $signStr= "";

            if($bank_code != ""){
                $signStr = $signStr."bank_code=".$bank_code."&";
            }
            if($client_ip != ""){
                $signStr = $signStr."client_ip=".$client_ip."&";
            }
            if($extend_param != ""){
                $signStr = $signStr."extend_param=".$extend_param."&";
            }
            if($extra_return_param != ""){
                $signStr = $signStr."extra_return_param=".$extra_return_param."&";
            }

            $signStr = $signStr."input_charset=".$input_charset."&";
            $signStr = $signStr."interface_version=".$interface_version."&";
            $signStr = $signStr."merchant_code=".$merchant_code."&";
            $signStr = $signStr."notify_url=".$notify_url."&";
            $signStr = $signStr."order_amount=".$order_amount."&";
            $signStr = $signStr."order_no=".$order_no."&";
            $signStr = $signStr."order_time=".$order_time."&";

            if($pay_type != ""){
                $signStr = $signStr."pay_type=".$pay_type."&";
            }

            if($product_code != ""){
                $signStr = $signStr."product_code=".$product_code."&";
            }
            if($product_desc != ""){
                $signStr = $signStr."product_desc=".$product_desc."&";
            }

            $signStr = $signStr."product_name=".$product_name."&";

            if($product_num != ""){
                $signStr = $signStr."product_num=".$product_num."&";
            }
            if($redo_flag != ""){
                $signStr = $signStr."redo_flag=".$redo_flag."&";
            }
            if($return_url != ""){
                $signStr = $signStr."return_url=".$return_url."&";
            }

            $signStr = $signStr."service_type=".$service_type;

            if($show_url != ""){

                $signStr = $signStr."&show_url=".$show_url;
            }

            //echo $signStr."<br>"; die;



/////////////////////////////   获取sign值（RSA-S加密）  /////////////////////////////////

            $merchant_private_key= openssl_get_privatekey($priKey);

            openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);

            $sign = base64_encode($sign_info);

            $urlApi = 'https://pay.dinpay.com/gateway?input_charset=UTF-8';
            $paramArr = array(
                'sign'=> $sign,
                'merchant_code' => $merchant_code,
                'bank_code' =>$bank_code,
                'order_no' =>$order_no,
                'order_amount'=>$order_amount,
                'service_type'=>$service_type,
                'input_charset'=>$input_charset,
                'notify_url' =>$notify_url,
                'interface_version' =>$interface_version,
                'sign_type' =>$sign_type,
                'order_time' => $order_time,
                'product_name'=> $product_name,
                'client_ip'=> $client_ip,
                'extend_param'=>$extend_param,
                'extra_return_param'=>$extra_return_param,
                'pay_type' => $pay_type,
                'product_code'=>$product_code,
                'product_desc'=> $product_desc,
                'product_num'=> $product_num,
                'return_url' => $return_url,
                'show_url' => $show_url,
                'redo_flag'=>$redo_flag
            );

            $this->form($urlApi,$paramArr);

        }else{
            $this->error('无效单号');
        }

    }

    /**
     * 支付回调
     */
    public function notify_confirm(){
        //获取 支付路径
        $payment  = D('Payment')->info(array('method'=>'dinpaywy'),'config');

        $payment ['payment_config'] = unserialize ( $payment ['payment_config'] );


        $pubKey = $payment ['payment_config'] ['public_key'];

        $merchant_code	= $_POST["merchant_code"];

        $interface_version = $_POST["interface_version"];

        $sign_type = $_POST["sign_type"];

        $dinpaySign = base64_decode($_POST["sign"]);

        $notify_type = $_POST["notify_type"];

        $notify_id = $_POST["notify_id"];

        $order_no = $_POST["order_no"];

        $order_time = $_POST["order_time"];

        $order_amount = $_POST["order_amount"];

        $trade_status = $_POST["trade_status"];

        $trade_time = $_POST["trade_time"];

        $trade_no = $_POST["trade_no"];

        $bank_seq_no = $_POST["bank_seq_no"];

        $extra_return_param = $_POST["extra_return_param"];


/////////////////////////////   参数组装  /////////////////////////////////
        /**
        除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
         */

        $signStr = "";

        if($bank_seq_no != ""){
            $signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
        }

        if($extra_return_param != ""){
            $signStr = $signStr."extra_return_param=".$extra_return_param."&";
        }

        $signStr = $signStr."interface_version=".$interface_version."&";

        $signStr = $signStr."merchant_code=".$merchant_code."&";

        $signStr = $signStr."notify_id=".$notify_id."&";

        $signStr = $signStr."notify_type=".$notify_type."&";

        $signStr = $signStr."order_amount=".$order_amount."&";

        $signStr = $signStr."order_no=".$order_no."&";

        $signStr = $signStr."order_time=".$order_time."&";

        $signStr = $signStr."trade_no=".$trade_no."&";

        $signStr = $signStr."trade_status=".$trade_status."&";

        $signStr = $signStr."trade_time=".$trade_time;




        $dinpay_public_key = openssl_get_publickey($pubKey);

        $flag = openssl_verify($signStr,$dinpaySign,$dinpay_public_key,OPENSSL_ALGO_MD5);

        if($flag==true){
            $id =$order_no;
            $order = D('Order')->info(array('id'=>$id));
            if($order['amount'] != $order_amount){
                D('Order')->updateData(array('id'=>$id),array('pay_status'=>'F'));
                exit('eposit successful');
            }

            if($order['pay_status'] !='Y'){

                $sql = D('Order')->updateData(array('id'=>$id),array('pay_status'=>'Y'));

                if($sql){
                    echo 'eposit successful';exit;
                }
            }else{
                echo 'deposit failed';exit;
            }

        } else{
            echo 'deposit failed';exit;
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