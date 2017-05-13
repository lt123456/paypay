<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 导航模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class   PaymentModel extends Model{

    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  index.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: index.html?r=5
     *
     * @param sting|Model  $model   模型名或模型实例
     * @param array        $where   where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order   排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param array        $base    基本的查询条件
     * @param boolean      $field   单表模型用不到该参数,要用在多表join时为field()方法指定参数
     * @author 朱亚杰 <xcoolcc@gmail.com>
     *
     * @return array|false
     * 返回数据集
     */
     public  function lists($where=array(),$field=true,$order="id desc"){
        $options    =   array();
        $REQUEST    =   (array)I('request.');


        $options['where'] =$where;
        if( empty($options['where'])){
            unset($options['where']);
        }
        $total        =   $this->where($options['where'])->count();

        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $page = new \Think\Page($total, 40, $REQUEST);
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();

         $return['_page'] =  $p ? $p :'';
         $return['_total']= $total;

        $options['limit'] = $page->firstRow.','.$page->listRows;


        $return['lists']  = $this->where($options['where'])->field($field)->order($order)->limit($options['limit'])->select();
         return $return;
    }

    /**
     * 获取信息
     * @param $where
     * @param string $field
     * @return mixed
     */
    public function info($where,$field=true){
        return $this->where($where)->field($field)->find();
    }

    /**
     * 更新数据
     * @param $where
     * @param $map
     * @return bool|false|int
     */
    public function  updateDate($where,$map){

        return $this->where($where)->save($map);
    }

    public function   sec($field,$order="sort asc"){
        return $this->field($field)->order($order)->select();
    }


}
