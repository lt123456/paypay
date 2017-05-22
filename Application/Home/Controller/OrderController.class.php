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
class OrderController extends HomeController {

	//系统首页
    public function query(){



        if(IS_POST){

             $this->xssRequest();
            $orderId = I('orderId');


            if($orderId){
                // 获取 订单
                $orderArr['id'] = $orderId;
                $order = D('Order')->info($orderArr);

                // 判断
                $where['status']= 'Y';
                $field = 'method';
                $payments =  D('Payment')->sec($where,$field);
                $allowWay = array();
                foreach($payments as $k=>$v){
                    $allowWay[$k] = $v['method'];

                }

                //获取 支付路径
                 $payment  = D('Payment')->info(array('method'=>$order['pay_type']),'url,domain');

                if($order['pay_status'] == 'N' && in_array($order['pay_type'],$allowWay)){
                    $this->form($payment,$order);
                }else {
                    $this->error('无效单号');
                }
            }else{
                $this->error('无效单号');
            }

        }else{
            $this->error('请注意姿势');
        }

    }

    /**
     * 提交表单
     */
    public function  form($payment,$order){

        echo '<form id="pay_form" method="post" action="'.trim($payment['domain']).$payment['url'].'"><input type="hidden" name="orderid"  value="'.$order["id"].'" /><input type="hidden" name="tokenSign"  value="8812662dcf3e5db0247c0f85909363fc" /></form>
			<script type="text/javascript">
				document.getElementById("pay_form").submit();
			</script>';

    }

    /**
     * 支付成功跳转
     */
    public  function  success(){

        $this->display('Index/success');

    }
    public function  checkConfirmOrder() {

        $order['id'] = I('orderId');



        $order = D('Order')->info($order,'pay_status');


        if($order['pay_status'] =='Y'){
            $arr = array('status'=>1,'message'=>'充值成功');

            $this->ajaxReturn($arr);
        }else{
            return $this->error('失败');
        }
    }



}
