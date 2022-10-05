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
                            <th nowrap class="text-left   xCNRptColumnHeader">สั่งขายที่สาขา</th>
                            <th nowrap class="text-left   xCNRptColumnHeader">เลขที่เอกสาร</th>
                            <th nowrap class="text-left   xCNRptColumnHeader">วันที่เอกสาร</th>
                            <th nowrap class="text-center   xCNRptColumnHeader">อ้างอิงใบขาย</th>
                            <th nowrap class="text-center   xCNRptColumnHeader">วันที่อ้างอิง</th>
                            <th nowrap class="text-left   xCNRptColumnHeader">สั่งขายให้ลูกค้า</th>
                            <th nowrap class="text-right   xCNRptColumnHeader"></th>
                            <th nowrap class="text-right   xCNRptColumnHeader"></th>
                            <th nowrap class="text-center   xCNRptColumnHeader"></th>
                            <th nowrap class="text-center   xCNRptColumnHeader"></th>
                            <th nowrap class="text-center   xCNRptColumnHeader">เบอร์โทร</th>
                            <th nowrap class="text-center   xCNRptColumnHeader"></th>
                        </tr>
                        <tr>
                            <th nowrap class="text-left   xCNRptColumnHeader"></th>
                            <th nowrap class="text-left   xCNRptColumnHeader">รหัสสินค้า</th>
                            <th nowrap class="text-left   xCNRptColumnHeader">ชื่อสินค้า</th>
                            <th nowrap class="text-center   xCNRptColumnHeader"></th>
                            <th nowrap class="text-center   xCNRptColumnHeader">บาร์โค้ด</th>
                            <th nowrap class="text-left   xCNRptColumnHeader">หน่วย</th>
                            <th nowrap class="text-right   xCNRptColumnHeader">ราคาขาย/หน่วย</th>
                            <th nowrap class="text-right   xCNRptColumnHeader">ราคาทุน/หน่วย</th>
                            <th nowrap class="text-right   xCNRptColumnHeader">จำนวน</th>
                            <th nowrap class="text-right   xCNRptColumnHeader">ยอดขาย</th>
                            <th nowrap class="text-right   xCNRptColumnHeader">ส่วนลด</th>
                            <th nowrap class="text-right   xCNRptColumnHeader">รวม</th>
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
                      

                                <?php if($aValue['PARTTITIONBYDOC_COUNT'] == '1'){ ?> 
                                <tr class = 'xCNRptLastGroupTr'>
                                    <td nowrap class="text-left   xCNRptDetail xCNRptSumFooter"><?php echo $aValue["FTBchName"]; ?></td>
                                    <td nowrap class="text-left   xCNRptDetail xCNRptSumFooter"><?php echo $aValue["FTXshDocNo"]; ?></td>
                                    <td nowrap class="text-left   xCNRptDetail xCNRptSumFooter"><?php echo  date("d/m/Y", strtotime($aValue["FDXshDocDate"])); ?></td>
                                    <td nowrap class="text-left   xCNRptDetail xCNRptSumFooter"><?php echo $aValue["FTXshRefDocNo"]; ?></td>
                                    <td nowrap class="text-center   xCNRptDetail xCNRptSumFooter"><?php ($aValue["FDXshRefDocDate"] == '') ? '-' : date("d/m/Y", strtotime($aValue["FDXshRefDocDate"])); ?></td>
                                    <td nowrap class="text-left   xCNRptDetail xCNRptSumFooter" colspan="5"><?php echo $aValue["FTCstName"]; ?></td>
                                    <td nowrap class="text-center   xCNRptDetail xCNRptSumFooter" colspan="2"><?php echo $aValue["FTXshCstTel"]; ?></td>
                                </tr>
                                <?php 
                                }?>

                                <tr>
                                    <td nowrap class="text-left   xCNRptDetail"></td>
                                    <td nowrap class="text-left   xCNRptDetail"><?php echo $aValue["FTPdtCode"]; ?></td>
                                    <td nowrap class="text-left   xCNRptDetail"><?php echo $aValue["FTPdtName"]; ?></td>
                                    <td nowrap class="text-left   xCNRptDetail"></td>
                                    <td nowrap class="text-left   xCNRptDetail"><?php echo $aValue["FTXsdBarCode"]; ?></td>
                                    <td nowrap class="text-left   xCNRptDetail"><?php echo $aValue["FTPunName"]; ?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?=number_format($aValue['FCXsdSetPrice'], $nOptDecimalShow)?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?=number_format($aValue['FCXsdCostIn'], $nOptDecimalShow)?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?=number_format($aValue['FCXsdQty'], $nOptDecimalShow)?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?=number_format($aValue['FCXsdAmount'], $nOptDecimalShow)?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?=number_format($aValue['FCXsdDiscount'], $nOptDecimalShow)?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?=number_format($aValue['FCXshTotal'], $nOptDecimalShow)?></td>
                                </tr>
                            
                                <!--รวมยอดตามสาขา-->
                                <?php if($aValue['PARTTITIONBYDOC'] == $aValue['PARTTITIONBYDOC_COUNT']){ ?>
                                    <tr class="xCNRptSumFooterTrTop">
                                        <td class="xCNRptSumFooter" colspan="8">ยอดรวม : <?=$aValue["FTXshDocNo"]; ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aValue['FCXsdQty_SubTotal'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aValue['FCXsdAmount_SubTotal'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aValue['FCXsdDiscount_SubTotal'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aValue['FCXshTotal_SubTotal'], $nOptDecimalShow); ?></td>
                                    </tr>
                                    <tr class="xCNRptSumFooterTrBottom"></tr>
                                <?php } ?>
                                    
                            <?php endforeach; ?>
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

            <?php if ((isset($aDataFilter['tCstCodeFrom']) && !empty($aDataFilter['tCstCodeFrom'])) || (isset($aDataFilter['tCstCodeTo']) && !empty($aDataFilter['tCstCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ลูกค้า =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom'] . ' : </span>' . $aDataFilter['tCstNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstTo'] . ' : </span>' . $aDataFilter['tCstNameTo']; ?></label>
                    </div>
                </div>
            <?php }; ?>

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

            <?php if( (isset($aDataFilter['tRptPdtCodeFrom']) && !empty($aDataFilter['tRptPdtCodeFrom'])) && (isset($aDataFilter['tRptPdtCodeTo']) && !empty($aDataFilter['tRptPdtCodeTo']))) { ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล สินค้า ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeFrom'].' : </span>'.$aDataFilter['tRptPdtNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeTo'].' : </span>'.$aDataFilter['tRptPdtNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tPdtGrpCodeFrom']) && !empty($aDataFilter['tPdtGrpCodeFrom'])) && (isset($aDataFilter['tPdtGrpCodeTo']) && !empty($aDataFilter['tPdtGrpCodeTo']))) { ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล กลุ่มสินค้า ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtGrpFrom'].' : </span>'.$aDataFilter['tPdtGrpNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtGrpTo'].' : </span>'.$aDataFilter['tPdtGrpNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>


            <?php if( (isset($aDataFilter['tPdtTypeCodeFrom']) && !empty($aDataFilter['tPdtTypeCodeFrom'])) && (isset($aDataFilter['tPdtTypeCodeTo']) && !empty($aDataFilter['tPdtTypeCodeTo']))) { ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล ประเภทสินค้า ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtTypeFrom'].' : </span>'.$aDataFilter['tPdtTypeNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtTypeTo'].' : </span>'.$aDataFilter['tPdtTypeNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

        </div>
    </div>
</div>