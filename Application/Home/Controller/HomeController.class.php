<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller {

	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}


    protected function _initialize(){
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置

        if(!C('WEB_SITE_CLOSE')){
            $this->error('站点已经关闭，请稍后访问~');
        }
    }

	/* 用户登录检测 */
	protected function login(){
		/* 用户登录检测 */
		is_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
	}
    public  function xssRequest(){
        $urlArr =  explode('/',$_SERVER['HTTP_REFERER']);

        if(!in_array(C('ALLOW_URL'),$urlArr)){
            $this->error('未知错误');
        }
    }

    /**
     * 生成二维码
     * @param string $url
     * @param int $level
     * @param int $size
     */
    public function qrcode($url,$order_id,$level=3,$size=5){

        Vendor('phpqrcode.phpqrcode');

        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        //echo $_SERVER['REQUEST_URI'];

        $path = './Uploads/Code/';
        $filename =$path.$order_id.'.png';
        $object = new \QRcode();
        $object->png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        return  trim($filename,'.');

    }

}
