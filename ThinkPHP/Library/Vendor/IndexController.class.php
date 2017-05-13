<?php
// +----------------------------------------------------------------------
//  jihexian
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.jihexian.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: wsyone <wsyone@foxmail.com> 
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 *
 */
class IndexController extends Controller {
/**
 * host 生成链接地址
 * level 容错级别 
 * size 图片大小
 */

 public function qrcode($url='http://www.jihexian.com',$level=3,$size=4){
  
  Vendor('phpqrcode.phpqrcode');
  
  $errorCorrectionLevel =intval($level) ;//容错级别 
  $matrixPointSize = intval($size);//生成图片大小 
//生成二维码图片 
  //echo $_SERVER['REQUEST_URI'];
  $object = new \QRcode();
  $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);   

 }

   
}

