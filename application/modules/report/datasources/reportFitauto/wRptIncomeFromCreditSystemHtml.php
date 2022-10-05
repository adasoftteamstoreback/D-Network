<?php
    $aDataReport        = $aDataViewRpt['aDataReport'];
    $aDataTextRef       = $aDataViewRpt['aDataTextRef'];
    $aDataFilter        = $aDataViewRpt['aDataFilter'];
    $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
?>
<style>
    .xCNFooterRpt {
        border-bottom: 7px double #ddd;
    }

    .table thead th,
    .table>thead>tr>th,
    .table tbody tr,
    .table>tbody>tr>td {
        border: 0px transparent !important;
    }

    .table>thead:first-child>tr:first-child>td,
    .table>thead:first-child>tr:first-child>th {
        border-top: 1px solid black !important;
        border-bottom: 1px solid black !important;
    }

    .table>tbody>tr.xCNTrSubFooter {
        border-top: 1px solid black !important;
        border-bottom: 1px solid black !important;
    }

    .table tbody tr.xCNRptLastGroupTr,
    .table>tbody>tr.xCNRptLastGroupTr>td {
        border: 0px solid black !important;
        border-bottom: 1px dashed #ccc !important;
    }

    .table>tbody>tr.xCNTrFooter {
        border-top: dashed 1px #333 !important;
        border-bottom: 1px solid black !important;
    }

    .table tbody tr.xCNRptSumFooterTrBottom,
    .table>tbody>tr.xCNRptSumFooterTrBottom>td {
        border: 0px solid black !important;
        border-bottom: 1px solid black !important;
    }

    .table tbody tr.xCNRptSumFooterTrTop,
    .table>tbody>tr.xCNRptSumFooterTrTop>td {
        border: 0px solid black !important;
        border-top: 1px solid black !important;
    }


    /*แนวนอน*/
    @media print {
        @page {
            size: landscape;
            margin: 1.5mm 1.5mm 1.5mm 1.5mm;
        }
    }
</style>

<div id="odvRptPreviewCustomerHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <div class="row">
                <?php include 'application\modules\report\datasources\Address\wRptAddress.php'; ?>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 report-filter">

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center">
                                <label class="xCNRptTitle"><?= $aDataTextRef['tTitleReport']; ?></label>
                            </div>
                        </div>
                    </div>

                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) : ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?= $aDataTextRef['tRptTaxSalePosFilterDocDateFrom'] ?></label> <label><?= date('d/m/Y', strtotime($aDataFilter['tDocDateFrom'])); ?> </label>&nbsp;
                                    <label class="xCNRptFilterHead"><?= $aDataTextRef['tRptTaxSalePosFilterDocDateTo'] ?></label> <label><?= date('d/m/Y', strtotime($aDataFilter['tDocDateTo'])); ?> </label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <label class="xCNRptDataPrint"><?= $aDataTextRef['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $aDataTextRef['tTimePrint'] . ' ' . date('H:i:s'); ?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th nowrap class="text-left     xCNRptColumnHeader">   <?=language('report/report/report', 'ลำดับ'); ?></th>
                            <th nowrap class="text-left     xCNRptColumnHeader">   <?=language('report/report/report', 'ชื่อบริษัท/ชื่อลูกค้า'); ?></th>
                            <th nowrap class="text-left     xCNRptColumnHeader">   <?=language('report/report/report', 'รหัส - ชื่อสาขา'); ?></th>
                            <th nowrap class="text-left     xCNRptColumnHeader">   <?=language('report/report/report', 'เลขที่บิล'); ?></th>
                            <th nowrap class="text-left     xCNRptColumnHeader">   <?=language('report/report/report', 'tRptTaxSalePosDoc'); ?></th>
                            <th nowrap class="text-left     xCNRptColumnHeader">   <?=language('report/report/report', 'tRptTaxSalePosDocDate'); ?></th>
                            <th nowrap class="text-left     xCNRptColumnHeader">   <?=language('report/report/report', 'รหัส - ชื่อสาขา(ของลูกค้า)'); ?></th>
                            <th nowrap class="text-left     xCNRptColumnHeader">   <?=language('report/report/report', 'รหัสสินค้า'); ?></th>
                            <th nowrap class="text-left     xCNRptColumnHeader">   <?=language('report/report/report', 'ชื่อสินค้า'); ?></th>
                            <th nowrap class="text-right    xCNRptColumnHeader">   <?=language('report/report/report', 'จำนวน'); ?></th>
                            <th nowrap class="text-right    xCNRptColumnHeader">   <?=language('report/report/report', 'ราคา/หน่วย'); ?></th>
                            <th nowrap class="text-right    xCNRptColumnHeader">   <?=language('report/report/report', 'ยอดขาย'); ?></th>
                            <th nowrap class="text-right    xCNRptColumnHeader">   <?=language('report/report/report', 'ส่วนลดรวม'); ?></th>
                            <th nowrap class="text-right    xCNRptColumnHeader">   <?=language('report/report/report', 'ยอดขายสุทธิ'); ?></th>
                            <th nowrap class="text-right    xCNRptColumnHeader">   <?=language('report/report/report', 'ต้นทุน/หน่วย'); ?></th>
                            <th nowrap class="text-right    xCNRptColumnHeader">   <?=language('report/report/report', 'ต้นทุน/หน่วยรวมภาษี'); ?></th>
                            <th nowrap class="text-right    xCNRptColumnHeader">   <?=language('report/report/report', 'ต้นทุนรวม'); ?></th>
                            <th nowrap class="text-right    xCNRptColumnHeader">   <?=language('report/report/report', 'กำไร'); ?></th>
                            <th nowrap class="text-right    xCNRptColumnHeader">   <?=language('report/report/report', '%กำไรเทียบทุนรวม'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php $tKeepCstCode = ''; ?>
                            <?php $tKeepDocNo   = ''; ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <?php  
                                    //รวมคอลัมน์ - ลูกค้า
                                    if($aValue['PARTTITIONBYCSTCODE'] == 1 || $aValue['PARTTITIONBYCSTCODE'] == 0){
                                        $nRowspan   = '';
                                    }else{
                                        $nRowspan   = "rowspan=".$aValue['PARTTITIONBYCSTCODE'];
                                    } 

                                    //รวมคอลัมน์ - เอกสาร
                                    if($aValue['PARTTITIONBYDOC'] == 1 || $aValue['PARTTITIONBYDOC'] == 0){
                                        $nRowspanDocNo   = '';
                                    }else{
                                        $nRowspanDocNo   = "rowspan=".$aValue['PARTTITIONBYDOC'];
                                    } 
                                ?>
                                <?php if($aValue['PARTTITIONBYCSTCODE'] >= 1){ ?>
                                    <?php if($tKeepCstCode != $aValue['FTCstCode']) { ?>
                                        <!-- <tr style="border: 0px solid black !important; border-top: 1px dashed #100d0d !important;">
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing"><?=($aValue['FTCstCompName'] == '') ? '-' : $aValue['FTCstCompName']?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing" colspan="12"><?=($aValue['FTCstName']  == '') ? '-' : $aValue['FTCstName'] ?></td>
                                        </tr> -->
                                    <?php } ?>
                                <?php }else{ ?>
                                    <tr>
                                <?php } ?>

                                    <?php if($aValue['PARTTITIONBYDOC'] >= 1) { ?>
                                        <?php if($tKeepDocNo != $aValue['FTXshDocNo'] ) { ?>
                                            <td nowrap <?=$nRowspanDocNo?> class="xCNRptDetail text-center"><?=$aValue['NUMBERDOC']?></td> 
                                            <td nowrap <?=$nRowspanDocNo?> class="xCNRptDetail text-left"> <?=($aValue['FTCstCompName'] == '') ? '-' : $aValue['FTCstCompName']?></td>
                                            <td nowrap <?=$nRowspanDocNo?> class="xCNRptDetail text-left"><?= ($aValue['FTBchCode'] == '') ? '-' : '('.$aValue['FTBchCode'].')' ?> <?= ($aValue['FTBchName'] == '') ? '' : $aValue['FTBchName']  ?></td>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php if($aValue['PARTTITIONBYDOC'] >= 1) { ?>
                                        <?php if($tKeepDocNo != $aValue['FTXshDocNo'] ) { ?>
                                            <td <?=$nRowspanDocNo?> nowrap class="xCNRptDetail text-left"><?= $aValue['FTXshDocNo'] ?></td>
                                            <td <?=$nRowspanDocNo?> nowrap class="xCNRptDetail"><?=($aValue['FTXshDocVatFull']  == '') ? '-' : $aValue['FTXshDocVatFull'] ?></td>
                                            <td <?=$nRowspanDocNo?> nowrap class="xCNRptDetail"><?= date('d/m/Y', strtotime($aValue['FDXshDocDate'])); ?></td>
                                            <td <?=$nRowspanDocNo?> nowrap class="xCNRptDetail"><?= ($aValue['FTBchCodeCst'] == '') ? '-' : '('.$aValue['FTBchCodeCst'].')' ?> <?= ($aValue['FTBchNameCst'] == '') ? '' : $aValue['FTBchNameCst']  ?></td>
                                        <?php } ?>
                                    <?php } ?>
                                    
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTPdtCode'] ?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTPdtName'] ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXsdQty'], $nOptDecimalShow) ?> <?=$aValue['FTPunName']?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXsdSetPrice'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXsdAmt'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXsdDis'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXsdNet'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshCost'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshCostIncludeVat'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshCostTotal'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshProfit'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshProfitPercent'], $nOptDecimalShow) ?></td>

                                    <!--รวมยอดตามบิล-->
                                    <?php if($aValue['PARTTITIONBYDOC_COUNT'] == $aValue['PARTTITIONBYDOC']){ ?>

                                        <?php 
                                            //%กำไรเทียบทุน
                                            if($aValue['FCXshCostTotal_Doc_Footer'] == 0){
                                                $nProFit_Doc_Footer = '100';
                                            }else{
                                                $nProFit_Doc_Footer = ($aValue['FCXshProfit_Doc_Footer'] * 100) / $aValue['FCXshCostTotal_Doc_Footer'];
                                            } 
                                        ?>

                                        <tr >
                                            <td colspan="8"></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"> รวมบิล </td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXsdQty_Doc_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXsdSetPrice_Doc_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXsdAmt_Doc_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXshDis_Doc_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXshGrand_Doc_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXshCost_Doc_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXshCostIncludeVat_Doc_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXshCostTotal_Doc_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXshProfit_Doc_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($nProFit_Doc_Footer, $nOptDecimalShow) ?></td>
                                        </tr>
                                        <!-- <tr>
                                            <td colspan="12"></td>
                                            <td colspan="6" class="xCNRptDetail xCNRptGrouPing text-right">ส่วนลดท้ายบิล : <?= number_format($aValue['FCXshDis'], $nOptDecimalShow) ?> บาท</td>
                                        </tr>
                                        <tr class="xCNRptLastGroupTr">
                                            <td colspan="12"></td>
                                            <td colspan="6" class="xCNRptDetail xCNRptGrouPing text-right"><?=$aValue['FTRcvName']?> : <?= number_format($aValue['FCXshGrand'], $nOptDecimalShow) ?> บาท</td>
                                        </tr> -->
                                    <?php } ?>

                                    <!--รวมยอดตามลูกค้า-->
                                    <?php if($aValue['PARTTITIONBYCSTCODE'] == $aValue['PARTTITIONBYCST_COUNT']){ ?>
                                        <?php 
                                            //%กำไรเทียบทุน
                                            if($aValue['FCXshCostTotal_CST_Footer'] == 0){
                                                $nProFit_CST_Footer = '100';
                                            }else{
                                                $nProFit_CST_Footer = ($aValue['FCXshProfit_CST_Footer'] * 100) / $aValue['FCXshCostTotal_CST_Footer'];
                                            } 
                                        ?>
                                        
                                        <tr class="xCNRptLastGroupTr">
                                            <td colspan="3" nowrap class="xCNRptDetail xCNRptGrouPing text-left">รวม<?=($aValue['FTCstName']  == '') ? '-' : $aValue['FTCstName'] ?></td>
                                            <td colspan="6"></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXsdQty_CST_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXsdAmt_CST_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXshDis_CST_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXshGrand_CST_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXshCostTotal_CST_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($aValue['FCXshProfit_CST_Footer'], $nOptDecimalShow) ?></td>
                                            <td nowrap class="xCNRptDetail xCNRptGrouPing text-right"><?= number_format($nProFit_CST_Footer, $nOptDecimalShow) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tr>
                                <?php $tKeepCstCode     = $aValue['FTCstCode']; ?>           
                                <?php $tKeepDocNo       = $aValue['FTXshDocNo']; ?>    
                            <?php } ?>

                            <?php 
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                if ($nPageNo == $nTotalPage) { ?>
                                    <?php 
                                        //%กำไรเทียบทุน
                                        if($aValue['FCXshCostTotal_Footer'] == 0){
                                            $nProFit_Footer = '100';
                                        }else{
                                            $nProFit_Footer = ($aValue['FCXshProfit_Footer'] * 100) / $aValue['FCXshCostTotal_Footer'];
                                        } 
                                    ?>

                                    <tr class="xCNRptSumFooterTrTop">
                                        <td nowrap class="xCNRptDetail"><strong><?php echo language('report/report/report', 'รวมทั้งสิ้น'); ?><strong></td>
                                        <td nowrap class="xCNRptDetail" colspan="8"></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXsdQty_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXsdAmt_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshDis_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshGrand_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshCostTotal_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshProfit_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($nProFit_Footer, $nOptDecimalShow) ?></strong></td>
                                    </tr>
                                    <tr class="xCNRptSumFooterTrBottom"></tr>
                                <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?= $aDataTextRef['tRptNoData']; ?></td>
                            </tr>
                        <?php }; ?>
                    </tbody>
                </table>
            </div>

            <div class="xCNRptFilterTitle">
                <div class="text-left">
                    <label class="xCNTextConsOth"><?= $aDataTextRef['tRptConditionInReport']; ?></label>
                </div>
            </div>

            <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
            <?php if (isset($aDataFilter['tBchCodeSelect']) && !empty($aDataFilter['tBchCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['tBchCodeSelect']) ? $aDataFilter['tBchNameSelect'] : $aDataTextRef['tRptAll']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

             <!-- ============================ ฟิวเตอร์ข้อมูล ลูกค้า ============================ -->
             <?php if (isset($aDataFilter['tCstCodeSelect']) && !empty($aDataFilter['tCstCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom']; ?> : </span> <?php echo ($aDataFilter['tCstCodeSelect']) ? $aDataFilter['tCstNameSelect'] : $aDataTextRef['tRptAll']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>