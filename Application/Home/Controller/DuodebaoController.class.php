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
class DuodebaoController extends HomeController {

    //系统首页
    public function wechatSumbit()
    {
        $this->xssRequest();
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
            $pay_type = 'weixin_scan';

            $res = $this->comm($order,$pay_type);

            if(empty($res)){
                return $this->error('生成二维码失败');
            }else{

                $returnArr['code'] = $res;
                $returnArr['orderId'] = $orderId;
                $returnArr['money'] = $order['amount'];

                $this->assign('result',$returnArr);
                $this->display('Index/wx');
            }


        }else{
            $this->error('无效单号');
        }

    }



    public function  comm($order,$pay_type){
        //获取 支付路径
        $payment  = D('Payment')->info(array('method'=>$order['pay_type']),'url,config');
        $payment ['payment_config'] = unserialize ( $payment ['config'] );
        $priKey = $payment ['payment_config'] ['private_key'];
        $pubKey = $payment ['payment_config'] ['public_key'];
        $noid = $payment ['payment_config'] ['noid'];

        $priKey = openssl_get_privatekey ( $priKey );

        $merchant_code = $noid;
//wxpay
        $service_type = $pay_type;

        $notify_url = U('Duodebao/notify_confirm');

        $interface_version = "V3.1";

        $sign_type = "RSA-S";

        $order_no = $order ['id'];

        $order_time = date ( "Y-m-d H:i:s", $order ['addtime'] );
        $client_ip = $_SERVER["REMOTE_ADDR"];


        $order_amount = $order ['amount'];

        $product_name = "在线充值";

        $product_code = "";

        $product_num = "";

        $product_desc = "";

        $extra_return_param = "";

        $extend_param = "";

        $signStr = "";
        $signStr = $signStr."client_ip=".$client_ip."&";

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

        $postdata = $postdata . 'product_name=' . $product_name.'&';
        $postdata = $postdata . 'client_ip='.$client_ip;

//echo "发送到智付的数据为：" . "<br>" . $postdata . "<br>";die;

        $ch = curl_init ();
//

        curl_setopt ( $ch, CURLOPT_URL, "https://api.ddbill.com/gateway/api/scanpay" );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HEADER, false );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postdata );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        $response = curl_exec ( $ch );
        $res = simplexml_load_string ( $response );
        curl_close ( $ch );

        if(empty($res)){
            $this->error('支付失败!');
        }

        $resp_code = $res->response->result_code;

        if($resp_code=="0"){
            $qrcode=$res->response->qrcode;

            $pic =  $this->qrcode($qrcode,$order['id']);

            return $pic;

        }else{
            return  $this->error($res->response->result_desc);

        }

    }

    //系统首页
    public function AliSubmit()
    {
        $this->xssRequest();

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
            $pay_type = 'alipay_scan';

            $res = $this->comm($order,$pay_type);

            if(empty($res)){
                return $this->error('生成二维码失败');
            }else{

                $returnArr['code'] = $res;
                $returnArr['orderId'] = $orderId;
                $returnArr['money'] = $order['amount'];

                $this->assign('result',$returnArr);
                $this->display('Index/zfb');
            }


        }else{
            $this->error('无效单号');
        }

    }

    /**
     *
     */
    public function bankSubmit(){
        $this->xssRequest();

        $orderId = I('orderid');
        $tokenSign = I('tokenSign');

        if($tokenSign !== '8812662dcf3e5db0247c0f85909363fc'){

            $this->error('系统错误');
        }
        if($orderId) {
            // 获取 订单
            $orderArr['id'] = $orderId;
            $order = D('Order')->info($orderArr);
            if (empty($order)) {
                $this->error('订单异常!');
            }
            //获取 支付路径
            $payment  = D('Payment')->info(array('method'=>$order['pay_type']),'url,config');
            $payment ['payment_config'] = unserialize ( $payment ['config'] );
            $priKey = $payment ['payment_config'] ['private_key'];
            $pubKey = $payment ['payment_config'] ['public_key'];
            $noid = $payment ['payment_config'] ['noid'];

            $priKey = openssl_get_privatekey ( $priKey );

            $merchant_code = $noid;
//wxpay
            $service_type = "direct_pay";

            $interface_version = "V3.0";

            $sign_type = "RSA-S";

            $input_charset = "UTF-8";

            $notify_url = U('Duodebao/notify_confirm');

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
            $urlApi = 'https://pay.ddbill.com/gateway?input_charset=UTF-8';
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

        D('OrderCallback')->addData();
        //获取 支付路径
        $payment  = D('Payment')->info(array('method'=>'renxinwx'),'config');

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
                echo 'failed';
            }
        }else{
            echo 'failed';
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