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
class  JumiyunController extends HomeController {

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
            $banktype = 'WEIXIN';

            $this->comm($order,$banktype);


        }else{
            $this->error('无效单号');
        }

    }

    public function  comm($order,$paytype){
        //获取 支付路径

        $payment  = D('Payment')->info(array('method'=>$order['pay_type']),'url,config');

        $config = unserialize($payment['config']);

        $merNo = $config['parter'];
        $key = $config['private'];



        $amout= $order['amount']*100 ;//交易金额，分
        $attach='pay';//附加数据
        $businessOrderNo= $order['id'];//商户订单号，注意同一个订单不能提交多次
        $notifyurl= U('Jumiyun/notify_confirm');//异步通知地址

//获得签名前的原串
        $signStr="amount=".$amout."&attach=".$attach."&businessOrderNo=".$businessOrderNo."&merNo=".$merNo."&notifyurl=".$notifyurl."&paytype=".$paytype;
        $signStr=$signStr.$key;//得到签名原串
        $signCode=md5($signStr);//签名
//开始生成POST数据包
        $param = array(
            'amount'=>$amout,
            'attach'=>$attach,
            'businessOrderNo'=>$businessOrderNo,
            'merNo'=>$merNo,
            'notifyurl'=>$notifyurl,
            'paytype'=>$paytype,
            'signature'=>$signCode
        );

        $url="http://pay.gdgdxy.com/iApppay/PayOrder";
        $param=json_encode($param);//将发送的数据包转换为JSON格式
        list($returnCode, $returnContent) = $this->http_post_json($url,$param);


        $result = json_decode($returnContent,true);
        header("location:".$result['url']);

    }
    public  function http_post_json($url, $jsonStr)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonStr)
            )
        );
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return array($httpCode, $response);
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
            $paytype='alipay';//支付类型，alipay为支付宝,wx为微信，暂时不支持微信
            $this->comm($order,$paytype);
        }else{
            $this->error('无效单号');
        }

    }

    /**
     * 支付回调
     */
    public function notify_confirm(){
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