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
class IndexController extends HomeController {

	//系统首页
    public function index(){

        $host =  get_host_name();

        $shopUrl =   D('Payment')->where(array('domain'=>$host))->find();


        if($shopUrl){
            $this->skipUrl  =  $shopUrl['shop_url'] ? $shopUrl['shop_url'] : 'http://dreamspark.ren';

            $this->display('shop');

        }else{
            // 生成token;防止伪造
            createToken();

            // 获取支付方式
            $where['status']= 'Y';
            $field = 'id,method,paytype';
            $this->payments =  D('Payment')->sec($where,$field);

            if(match()){
                $this->display('mobile');
            }else{
                $this->display();
            }
        }

    }


    public function orderSubmit(){

        $data = I();
         $this->xssRequest();
        //过滤字段
        $this->filter($data);
        //修改数据
        $orderData = $this->setData($data);


        $orderId =  D('Order')->add($orderData);

        if($orderId){
            $returnArr= array(
                'status'=>'1',
                'url'=>U('order/query'),
                'orderId' =>$orderId
            );
            $this->ajaxReturn($returnArr);
//            $this->success('下单成功!',$returnArr);

        }
        $this->error('下单失败');


    }


    /**
     * @param $data
     */
    protected   function filter($data){

        if($data['wether'] != 'Zxcvbnm123456'){
            $this->error('未知错误');
        }
        $cookie = $_COOKIE['onethink_home_Z-TOKEN'];
        if(strlen($cookie) !='34'){
            $this->error('未知C错误');
        }
        $auth = $_COOKIE['onethink_home_AUTH'];
        if($auth != 'dream'){
            $this->error('未知C错误');
        }
        if(empty($data['username'])){

            $this->error('请输入用户名');
        }
        if($data['username'] != $data['re_username']){
            $this->error('两次用户名不一致');
        };
        cookie('username',$data['username']);
        if(empty($data['order_amount'])){
            $this->error('请输入金额');
        }
        $where['status']= 'Y';
        $field = 'method';
        $payments =  D('Payment')->sec($where,$field);
        $allowWay = array();
        foreach($payments as $k=>$v){
            $allowWay[$k] = $v['method'];

        }
        if(!in_array($data['pay_type'],$allowWay)){
            $this->error('未知错误');
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    protected  function  setData($data){
        $order['username'] = $data['username'];
        $order['amount'] = $data['order_amount'];
        $order['pay_type'] = $data['pay_type'];
        $order['addtime'] = time();
        $order['pay_status'] = 'N';
        $order['sync_sttus'] ='N';
        return  $order;
    }

}