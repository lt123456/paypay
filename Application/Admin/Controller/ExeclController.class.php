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
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class ExeclController extends \Think\Controller {

    public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName =date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("PHPExcel.PHPExcel");

        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    /**
     *
     * 导出Excel
     */
     public function expOrder(){//导出Excel
        $xlsName  = "Order";
        $xlsCell  = array(
            array('id','账号序列'),
            array('username','充值账号'),
            array('amount','充值金额'),
            array('pay_status','是否支付'),
            array('sync_status','是否同步'),
            array('addtime','交易时间'),
            array('pay_type','支付方式'),

        );
        $xlsModel = M('Order');

        $xlsData  = $xlsModel->where(array('pay_status'=>'Y','addtime'=>array('gt',time()-30*24*3600)))->field('id,username,amount,pay_status,sync_status,addtime,pay_type')->select();
        foreach ($xlsData as $k => $v)
        {
            $xlsData[$k]['pay_status']= $this->payStatusMeans($v['pay_status']);
            $xlsData[$k]['sync_status']= $this->syncStatusMeans($v['sync_status']);
            $xlsData[$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
        }
        $this->exportExcel($xlsName,$xlsCell,$xlsData);

    }

    public function payStatusMeans($pay) {
        switch($pay){
            case 'N':
                $mean = '未支付';
                break;
            case 'Y':
                $mean = "已支付";
                break;
            case 'F':
                $mean = '内鬼';
                break;
            case 'T':
                $mean = '内鬼已解除';
                break;
        }
        return $mean;
    }
    public function syncStatusMeans($pay) {
        switch($pay){
            case 'N':
                $mean = '待同步';
                break;
            case 'Y':
                $mean = "已同步";
                break;

        }
        return $mean;
    }


}
