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

class PaymentController extends AdminController {

    /**
     * 订单列表
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){

        $field = "id,method,name,url,paytype,status";
        $this->payment = D('Payment')->sec($field,'sort asc');
        $this->meta_title = '支付方式管理';
        $this->display();
    }

    public function setCheck()
    {

        $data = I();
        if($data['ids']){

          D('Payment')->where(array('id'=>array('gt',0)))->save(array('status'=>'N'));
        $data['id'] = array('in',$data['ids']);



        $map['status'] = 'Y';

            $info = D('Payment')->updateDate($data,$map);

            if($info){
                $this->success('操作成功!');

            }
            $this->error('操作失败!');
        }else{
            $this->error('非法操作!');
        }
    }

    /**
     * 设置一条是否开启
     */
    public function  setCheckOne()
    {
        $data = I();



        $id['id'] = $data['id'];
        unset($data['id']);
        $info  = D('Payment')->info($id);

        if($info){
            $info = D('Payment')->updateDate($id,$data);

            if($info){
                $this->success('操作成功!');

            }
            $this->error('操作失败!');
        }else{
            $this->error('非法操作!');
        }
    }
    public function edit($id="") {

        if(IS_POST){
            $data= I();

            $where['id'] = $data['id'];
            $data['config'] =serialize($data['config']);

             $res = D('Payment')->updateDate($where,$data);

            if($res !==false){

                $this->success('操作成功',U('Payment/index'));
            }else{
                $this->error('操作失败',U('Payment/index'));
            }

        }

        $where['id'] = $id;
        $info  = D('Payment')->info($where);

        $this->info  = $info ? $info : '';

        $this->display();

    }
    /**
     * 配置排序
     * @author huajie <banhuajie@163.com>
     */
    public function sort(){

        if(IS_GET){

            $list = D('Payment')->field('id,name')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->meta_title = '配置排序';
            $this->display();
        }elseif (IS_POST){
            $ids = I('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = M('Payment')->where(array('id'=>$value))->setField('sort', $key+1);
            }
            if($res !== false){
                $this->success('排序成功',U('Payment/index'));
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }

    /**
     * 支付
     */
    public function  testPay(){

        $id = I('id');
        $info  = D('Payment')->info(array('id'=>$id));

        $orderData = $this->setData($info['method']);

        $orderId  =  D('Order')->add($orderData);

        $payUrl = trim($info['domain'].$info['url'],'');

//        var_dump($orderId);
//        var_dump($payUrl);
        $this->form($payUrl,$orderId);


    }
    /**
     * @param $data
     * @return mixed
     */
    protected  function  setData($pay_status){
        $order['username'] = 'test';
        $order['amount'] =  '5';
        $order['pay_type'] = $pay_status;
        $order['addtime'] = time();
        $order['pay_status'] = 'N';
        $order['sync_sttus'] ='N';
        return  $order;
    }
    /**
     * 提交表单
     */
    public function  form($payment,$order){

        echo '<form id="pay_form" method="post" action="'.$payment.'"><input type="hidden" name="orderid"  value="'.$order.'" /><input type="hidden" name="tokenSign"  value="8812662dcf3e5db0247c0f85909363fc" /></form>
			<script type="text/javascript">
				document.getElementById("pay_form").submit();
			</script>';

    }
    public function getFile(){

        if(IS_POST){

            $data = I();

            $url = $data['url'];
            $save_dir= './Application/Home/Controller/';
            $type =0 ;

            if(trim($url)==''){
                return $this->error('请输入地址');
            }
            $filename = array_pop(explode('/',$url));
            $a = explode('.',$filename);

            $filename =$a[0].'.class.php';


            if(trim($save_dir)==''){
                $save_dir='./';
            }
            if(0!==strrpos($save_dir,'/')){
                $save_dir.='/';
            }
            //创建保存目录
            if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
                return false;
            }
            if(file_exists($save_dir.$filename)){
                unlink($save_dir.$filename);
            }
            //获取远程文件所采用的方法
            if($type){
                $ch=curl_init();
                $timeout=5;
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
                $content=curl_exec($ch);
                curl_close($ch);
            }else{
                ob_start();
                readfile($url);
                $content=ob_get_contents();
                ob_end_clean();
            }
            $size=strlen($content);
            //文件大小
            $fp2=@fopen($save_dir.$filename,'a');
            fwrite($fp2,$content);
            fclose($fp2);
            unset($content,$url);
            $this->success('接口提交成功',U('Payment/index'));

        }else{

            $this->display();

        }


    }

}
