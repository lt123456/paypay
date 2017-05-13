<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 后台频道控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class OrderController extends AdminController {

    /**
     * 订单列表
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $data = I();

        $data['title'] = trim($data['title']);


        $where =array();

        if($data['pay']){

            if($data['pay'] =='T'){
                $where['pay_status'] = array('in','F,T');
            }else{
                $where['pay_status'] = $data['pay'];
            }

        }
        if($data['title']) {
            $where['_string']= '(username like  "%'.trim($data["title"],'').'%") or ( id like "%'.trim($data["title"],'').'%")';
        }
        if($data['pay_type']){
            $where['pay_type'] = $data['pay_type'];
        }
        $startTime  = $_GET['time-start']?$_GET['time-start']:date("Y-m-d H:i:s",strtotime('-100 day'));
        $stopTime = $_GET['time-end']?$_GET['time-end']:date("Y-m-d H:i:s");
//        var_dump($startTime);
//        var_dump($stopTime);
        $where['addtime'] = array('between',array(strtotime($startTime),strtotime($stopTime)));
        $order =  D('Order')->lists($where,'*','id desc');

        // 订单总数
        // 今日时间开始
         $total_start = date('Y-m-d 00:00:00',time());

        $this->today = D('Order')->where(array('pay_status'=>'Y','addtime'=>array('between',array(strtotime($total_start),time()))))->count();
        $today_money = D('Order')->where(array('pay_status'=>'Y','addtime'=>array('between',array(strtotime($total_start),time()))))->sum('amount');
        $this->today_money  = $today_money ? $today_money : 0 ;
        $this->total= D('Order')->where(array('pay_status'=>'Y'))->count();

        $this->total_money= D('Order')->where(array('pay_status'=>'Y'))->sum('amount');

         $this->payment =  D('Payment')->sec('method,name');
        $this->assign('order',$order);

        $this->meta_title = '订单管理';
        $this->display();
    }

    public function  updateStatus()
    {

        $data = I();

        //记录操作
        $uid = session('user_auth')['uid'];

        $order_id = $data['id'];

        if($data['pay_status'] == 'Y'){
            $type = '1';
        }

        if($data['sync_status'] == 'Y'){
            $type = '2';
        }
        if($data['pay_status'] == 'T'){
            $type = '3';
        }
        createOrderLog($uid,$order_id,$type);
        $id['id'] = $data['id'];
        unset($data['id']);
        $info  = D('Order')->info($id);

        if($info){
            $info = D('Order')->updateDate($id,$data);

            if($info){
                $this->success('操作成功!');

            }
            $this->error('操作失败!');
        }else{
            $this->error('非法操作!');
        }

    }
    /**
     * 订单列表
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function logs(){

        $data = I();

        if($data['title']) {
            $where['order_id']= $data['title'];
        }
        if($data['type']){
            $where['type'] = $data['type'];
        }
        $startTime  = $_GET['time-start']?$_GET['time-start']:date("Y-m-d H:i:s",strtotime('-100 day'));
        $stopTime = $_GET['time-end']?$_GET['time-end']:date("Y-m-d H:i:s");
        $where['create_time'] = array('between',array(strtotime($startTime),strtotime($stopTime)));
        $this->logs =  D('OrderLog')->lists($where,'*','id desc');



        $this->meta_title = '操作记录';
        $this->display();
    }



}
