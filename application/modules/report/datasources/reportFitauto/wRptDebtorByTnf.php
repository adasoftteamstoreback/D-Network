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

    .table>tbody>tr.xCNTrFooter {
        border-top: dashed 1px #333 !important;
        border-bottom: 1px solid black !important;
    }

    .table tbody tr.xCNRptSumFooterTrTop,
    .table>tbody>tr.xCNRptSumFooterTrTop>td {
        border: 0px solid black !important;
        border-top: 1px solid black !important;
    }

    .table tbody tr.xCNRptLastGroupTr,
    .table>tbody>tr.xCNRptLastGroupTr>td {
        border: 0px solid black !important;
        border-top: 1px dashed #ccc !important;
    }

    .table tbody tr.xCNRptSumFooterTrBottom,
    .table>tbody>tr.xCNRptSumFooterTrBottom>td {
        border: 0px solid black !important;
        border-bottom: 1px solid black !important;
    }


    /*แนวนอน*/
    @media print {
        @page {
            size: landscape;
            margin: 1.5mm 1.5mm 1.5mm 1.5mm;
        }
    }
</style>
<div id="odvRptPobyBchbyPdteHtml">
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
                            <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) : ?>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="text-center xCNRptFilter">
                                            <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateFrom']; ?> </span> <?php echo date("d/m/Y", strtotime($aDataFilter['tDocDateFrom'])); ?></label>
                                            <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateTo']; ?> </span> <?php echo date("d/m/Y", strtotime($aDataFilter['tDocDateTo'])); ?></label>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
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
                            <th nowrap class="text-left   xCNRptColumnHeader" style="width:10%;">ผู้จำหน่าย / เจ้าหนี้</th>
                            <th nowrap class="text-left   xCNRptColumnHeader" style="width:10%;">เลขที่เอกสาร</th>
                            <th nowrap class="text-center   xCNRptColumnHeader" style="width:10%;">วันที่เอกสาร</th>
                            <th nowrap class="text-left   xCNRptColumnHeader" style="width:10%;">อ้างอิงใบเบิกออก</th>
                            <th nowrap class="text-center   xCNRptColumnHeader" style="width:10%;">วันที่อ้างอิง</th>
                            <th nowrap class="text-right   xCNRptColumnHeader" style="width:10%;">ยอดยกเว้นภาษี</th>
                            <th nowrap class="text-right   xCNRptColumnHeader" style="width:10%;">ยอดแยกภาษี</th>
                            <th nowrap class="text-right   xCNRptColumnHeader" style="width:10%;">ภาษี</th>
                            <th nowrap class="text-right   xCNRptColumnHeader" style="width:10%;">ยอดรวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php
                            // Set ตัวแปร SumSub Footer
                            $nQty_SubTotal  = 0;
                            // Set ตัวแปร Sum Footer
                            $nQty_Footer    = 0;
                            // Check Branch 
                            $tChkBranch     = "";
                            $tChkProduct    = "";

                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                      
                                <tr>
                                    <td class="text-left xCNRptDetail"><?=(!empty($aValue['FTSplName']))? $aValue['FTSplName'] : '-' ?></td>
                                    <td nowrap class="text-left   xCNRptDetail"><?php echo $aValue["FTXshDocNo"]; ?></td>
                                    <td nowrap class="text-center   xCNRptDetail"><?php echo  date("d/m/Y", strtotime($aValue["FDXshDocDate"])); ?></td>
                                    <td class="text-left xCNRptDetail"><?=(!empty($aValue['FTXshRefDocNo']))? $aValue['FTXshRefDocNo'] : '-' ?></td>
                                    <td nowrap class="text-center   xCNRptDetail"><?php echo  date("d/m/Y", strtotime($aValue["FDXshRefDocDate"])); ?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?=number_format($aValue['FCXshAmtNV'], $nOptDecimalShow)?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?=number_format($aValue['FCXshVatable'], $nOptDecimalShow)?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?=number_format($aValue['FCXshVat'], $nOptDecimalShow)?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?=number_format($aValue['FCXshGrand'], $nOptDecimalShow)?></td>
                                </tr>
                            
                                <!--รวมยอดตามสาขา-->
                                <?php if($aValue['PARTTITIONBYSPL'] == $aValue['PARTTITIONBYSPL_COUNT']){ ?>
                                    <tr class="xCNRptSumFooterTrTop">
                                        <? if($aValue["FTSplName"] != '') {?>
                                        <td class="xCNRptSumFooter" colspan="5">ยอดรวม : <?=$aValue["FTSplName"]; ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aValue['FCXshAmtNV_SubTotal'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aValue['FCXshVatable_SubTotal'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aValue['FCXshVat_SubTotal'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aValue['FCXshGrand_SubTotal'], $nOptDecimalShow); ?></td>
                                        <?php }else{ ?>
                                        <td class="xCNRptSumFooter" colspan="6">ไม่พบลูกค้า</td>
                                        <?php } ?>
                                    </tr>
                                    <tr class="xCNRptSumFooterTrBottom"></tr>
                                <?php } ?>
                                    
                            <?php endforeach; ?>
                            <?php $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                            if ($nPageNo == $nTotalPage) { ?>
                                <tr>
                                    <td nowrap class="xCNRptDetail"><strong>รวมยอดทั้งสิ้น<strong></td>


                                    <td nowrap class="xCNRptDetail" colspan="4"></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshAmtNV_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshVatable_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshVat_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshGrand_Footer'], $nOptDecimalShow) ?></strong></td>

                                </tr>
                                <tr class="text-left xCNRptDetail" style=" border-left: 11px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                            <?php } ?>

                        <?php else : ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?php echo @$aDataTextRef['tRptAdjStkNoData']; ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="xCNRptFilterTitle">
                <div class="text-left">
                    <label class="xCNTextConsOth"><?= $aDataTextRef['tRptConditionInReport']; ?></label>
                </div>
            </div>
            <?php if (isset($aDataFilter['tBchCodeSelect']) && !empty($aDataFilter['tBchCodeSelect'])) : ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['bBchStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tBchNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ((isset($aDataFilter['tPdtSupplierCodeFrom']) && !empty($aDataFilter['tPdtSupplierCodeFrom'])) && (isset($aDataFilter['tPdtSupplierCodeTo']) && !empty($aDataFilter['tPdtSupplierCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ผู้จำหน่าย =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplFrom'] . ' : </span>' . $aDataFilter['tPdtSupplierNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTo'] . ' : </span>' . $aDataFilter['tPdtSupplierNameTo']; ?></label>
                    </div>
                </div>
            <?php }; ?>

            <?php if ((isset($aDataFilter['tPdtSgpCodeFrom']) && !empty($aDataFilter['tPdtSgpCodeFrom'])) || (isset($aDataFilter['tPdtSgpCodeTo']) && !empty($aDataFilter['tPdtSgpCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล กลุ่มผู้จำหน่าย =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplGrpForm'] . ' : </span>' . $aDataFilter['tPdtSgpNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplGrpTo'] . ' : </span>' . $aDataFilter['tPdtSgpNameTo']; ?></label>
                    </div>
                </div>
            <?php }; ?>

            <?php if ((isset($aDataFilter['tPdtStyCodeFrom']) && !empty($aDataFilter['tPdtStyCodeFrom'])) || (isset($aDataFilter['tPdtStyCodeTo']) && !empty($aDataFilter['tPdtStyCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ประเภทผู้จำหน่าย =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTypeForm'] . ' : </span>' . $aDataFilter['tPdtStyNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTypeTo'] . ' : </span>' . $aDataFilter['tPdtStyNameTo']; ?></label>
                    </div>
                </div>
            <?php }; ?>

            <?php if ((isset($aDataFilter['tCstCodeFrom']) && !empty($aDataFilter['tCstCodeFrom'])) || (isset($aDataFilter['tCstCodeTo']) && !empty($aDataFilter['tCstCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ลูกค้า =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom'] . ' : </span>' . $aDataFilter['tCstNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstTo'] . ' : </span>' . $aDataFilter['tCstNameTo']; ?></label>
                    </div>
                </div>
            <?php }; ?>

            <!-- ============================ ฟิวเตอร์ข้อมูล แคชเชียร์ ============================ -->
            <?php if ((isset($aDataFilter['tCashierCodeFrom']) && !empty($aDataFilter['tCashierCodeFrom'])) && (isset($aDataFilter['tCashierCodeTo']) && !empty($aDataFilter['tCashierCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCashierFrom'] . ' : </span>' . $aDataFilter['tCashierNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCashierTo'] . ' : </span>' . $aDataFilter['tCashierNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($aDataFilter['tCashierCodeSelect']) && !empty($aDataFilter['tCashierCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCashierFrom']; ?> : </span> <?php echo ($aDataFilter['bCashierStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tCashierCodeSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ===== ฟิวเตอร์ข้อมูล กลุ่มลูกค้า ========================== -->
            <?php if((isset($aDataFilter['tCstGrpCodeFrom']) && !empty($aDataFilter['tCstGrpCodeFrom'])) && (isset($aDataFilter['tCstGrpCodeTo']) && !empty($aDataFilter['tCstGrpCodeTo']))): ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptCstGrpForm') . ' : </span>' . $aDataFilter['tCstGrpNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptCstGrpTo') . ' : </span>' . $aDataFilter['tCstGrpNameTo']; ?></label>
                    </div>
                </div>
            <?php endif;?>

            <!-- ===== ฟิวเตอร์ข้อมูล ประเภทลูกค้า ========================== -->
            <?php if((isset($aDataFilter['tCstTypeCodeFrom']) && !empty($aDataFilter['tCstTypeCodeFrom'])) && (isset($aDataFilter['tCstTypeCodeTo']) && !empty($aDataFilter['tCstTypeCodeTo']))): ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptCstTypeForm') . ' : </span>' . $aDataFilter['tCstTypeNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptCstTypeTo') . ' : </span>' . $aDataFilter['tCstTypeNameTo']; ?></label>
                    </div>
                </div>
            <?php endif;?>

        </div>
    </div>
</div>