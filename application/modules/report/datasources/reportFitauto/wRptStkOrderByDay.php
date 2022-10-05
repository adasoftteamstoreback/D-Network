<?php
    $aDataReport        = $aDataViewRpt['aDataReport'];
    $aDataTextRef       = $aDataViewRpt['aDataTextRef'];
    $aDataFilter        = $aDataViewRpt['aDataFilter'];
    $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
?>
<style type="text/css">
    .xCNFooterRpt {border-bottom: 7px double #ddd;}
    .table thead th,.table>thead>tr>th,.table tbody tr,.table>tbody>tr>td {border: 0px transparent !important;}
    .table>thead:first-child>tr:first-child>td,.table>thead:first-child>tr:first-child>th {border-top: 1px solid black !important;border-bottom: 1px solid black !important;}
    .table>tbody>tr.xCNTrSubFooter {border-top: 1px solid black !important;border-bottom: 1px solid black !important;}
    .table>tbody>tr.xCNTrFooter {border-top: dashed 1px #333 !important;border-bottom: 1px solid black !important;}
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
                            <th nowrap class="text-left  xCNRptColumnHeader" style="font-size:17px !important;">รหัสสาขา/รหัสหมวดหมู่สินค้าย่อย</th>
                            <th nowrap class="text-left  xCNRptColumnHeader" colspan="11" style="font-size:17px !important;">ชื่อสาขา/ชื่อหมวดหมู่สินค้าย่อย</th>
                        </tr>
                        <tr>
                            <th class="text-center  xCNRptColumnHeader" style="width:25%; font-size:17px !important;"><?=language('report/report/report','tRptProductCode');?> - <?=language('report/report/report','tRptProductName');?></th> 
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:3%; font-size:17px !important; padding-right:0px; padding-left:0px !important;"><?=language('report/report/report','tRptBringF');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:3%; font-size:17px !important; padding-right:0px; padding-left:0px !important;"><?=language('report/report/report','tRptIn');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:3%; font-size:17px !important; padding-right:0px; padding-left:0px !important;"><?=language('report/report/report','tRptEx');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:3%; font-size:17px !important; padding-right:0px; padding-left:0px !important;"><?=language('report/report/report','tRptTrfReceived');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:3%; font-size:17px !important; padding-right:0px; padding-left:0px !important;"><?=language('report/report/report','tRptTrfPay');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:3%; font-size:17px !important; padding-right:0px; padding-left:0px !important;"><?=language('report/report/report','tRptSale');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:3%; font-size:17px !important; padding-right:0px; padding-left:0px !important;"><?=language('report/report/report','tRptStkReturn');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:3%; font-size:17px !important; line-height: 1; padding-right:0px; padding-left:0px !important;">ปรับเพิ่ม<br>(+)</th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:3%; font-size:17px !important; line-height: 1; padding-right:0px; padding-left:0px !important;">ปรับลด<br>(-)</th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:3%; font-size:17px !important; padding-right:0px; padding-left:0px !important;"><?=language('report/report/report','tRptInven');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])): ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue): ?>
                                <?php if ($aValue['PARTITION_BCH'] == '1') { ?>
                                    <tr style="border-top:1px solid #333 !important;border-bottom:1px solid #333 !important;">
                                        <td nowrap class="xCNRptDetail" style="font-size:17px !important;"><b><?=$aValue['FTBchCode']?></b></td>
                                        <td nowrap class="xCNRptDetail" colspan="11" style="font-size:17px !important;"><b><?= $aValue['FTBchName'] ?></b></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($aValue['PARTITION_CAT2'] == '1') { ?>
                                    <tr>
                                        <td nowrap class="xCNRptDetail" style="font-size:17px !important;"><b><?= ($aValue['FTPdtDptCode'] == '')? '-' : $aValue['FTPdtDptCode']?></b></td>
										<td nowrap class="xCNRptDetail" colspan="11" style="font-size:17px !important;"><b><?= ($aValue['FTPdtDptName'] == '')? 'ไม่ระบุ' : $aValue['FTPdtDptName'] ?></b></td>

                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td class="xCNRptDetail" style="font-size:17px !important;">&nbsp;&nbsp;(<?= $aValue['FTPdtCode'] ?>) - <?= $aValue['FTPdtName'] ?></td>
                                    <?php 
                                    $nDayEnd        = $aValue['FCStkQtyDayEnd'];
                                    $nMonthEnd      = $aValue['FCStkQtyMonEnd'];
                                    $nAmountEnd     = ($nDayEnd)+($nMonthEnd);
                                    $nStkQtyIn      = $aValue['FCStkQtyIn'];
                                    $nStkQtyOut     = $aValue['FCStkQtyOut'];
                                    $nStkQtyInfIn   = $aValue['FCStkQtyInfIn'];
                                    $nStkQtyInfOut  = $aValue['FCStkQtyInfOut'];
                                    $nStkQtySale    = $aValue['FCStkQtySale'];
                                    $nStkQtyCN      = $aValue['FCStkQtyCN'];
                                    $nStkQtyAdjUp   = $aValue['FCStkQtyAdjUp'];
                                    $nStkQtyAdjDown = $aValue['FCStkQtyAdjDown'];
                                    $nTotal         = ($nAmountEnd)+($nStkQtyIn)-($nStkQtyOut)+($nStkQtyInfIn)-($nStkQtyInfOut)-($nStkQtySale)+($nStkQtyCN)+($nStkQtyAdjUp)-($nStkQtyAdjDown);
                                    ?>
                                    <td nowrap class="text-right xCNRptDetail" style="font-size:17px !important;"><?= number_format($nAmountEnd,$nOptDecimalShow); ?></td>
                                    <td nowrap class="text-right xCNRptDetail" style="font-size:17px !important;"><?= number_format($nStkQtyIn,$nOptDecimalShow); ?></td>
                                    <td nowrap class="text-right xCNRptDetail" style="font-size:17px !important;"><?= number_format($nStkQtyOut,$nOptDecimalShow); ?></td>
                                    <td nowrap class="text-right xCNRptDetail" style="font-size:17px !important;"><?= number_format($nStkQtyInfIn,$nOptDecimalShow); ?></td>
                                    <td nowrap class="text-right xCNRptDetail" style="font-size:17px !important;"><?= number_format($nStkQtyInfOut,$nOptDecimalShow); ?></td>
                                    <td nowrap class="text-right xCNRptDetail" style="font-size:17px !important;"><?= number_format($nStkQtySale,$nOptDecimalShow); ?></td>
                                    <td nowrap class="text-right xCNRptDetail" style="font-size:17px !important;"><?= number_format($nStkQtyCN,$nOptDecimalShow); ?></td>
                                    <td nowrap class="text-right xCNRptDetail" style="font-size:17px !important;"><?= number_format($nStkQtyAdjUp,$nOptDecimalShow); ?></td>
                                    <td nowrap class="text-right xCNRptDetail" style="font-size:17px !important;"><?= number_format($nStkQtyAdjDown,$nOptDecimalShow); ?></td>
                                    <td nowrap class="text-right xCNRptDetail" style="font-size:17px !important;"><?= number_format($nTotal,$nOptDecimalShow); ?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php else : ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?= $aDataTextRef['tRptNoData']; ?></td>
                            </tr>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
            <div class="xCNRptFilterTitle">
                <div class="text-left">
                    <label class="xCNTextConsOth"><?= $aDataTextRef['tRptConditionInReport']; ?></label>
                </div>
            </div>
            <!-- ============================ ฟิวเตอร์ข้อมูล ตัวแทนขาย ============================ -->
            <?php if (isset($aDataFilter['tAgnCodeSelect']) && !empty($aDataFilter['tAgnCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAgnFrom']; ?> : </span> <?php echo ($aDataFilter['tAgnName']); ?></label>
                    </div>
                </div>
            <?php endif; ?>
            <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
            <?php if (isset($aDataFilter['tBchCodeSelect']) && !empty($aDataFilter['tBchCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['bBchStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tBchNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ((isset($aDataFilter['tWahCodeFrom']) && !empty($aDataFilter['tWahCodeFrom'])) && (isset($aDataFilter['tWahCodeTo']) && !empty($aDataFilter['tWahCodeTo']))) : ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล คลังสินค้า ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjWahFrom']; ?> : </span> <?php echo $aDataFilter['tWahNameFrom']; ?></label>
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjWahTo']; ?> : </span> <?php echo $aDataFilter['tWahNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ((isset($aDataFilter['tRptPdtCodeFrom']) && !empty($aDataFilter['tRptPdtCodeFrom'])) && (isset($aDataFilter['tRptPdtCodeTo']) && !empty($aDataFilter['tRptPdtCodeTo']))) : ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล สินค้า ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeFrom']; ?> : </span> <?php echo $aDataFilter['tRptPdtNameFrom']; ?></label>
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeTo']; ?> : </span> <?php echo $aDataFilter['tRptPdtNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ============================ ฟิวเตอร์ข้อมูล หมวดหมู่สินค้าหลัก ============================ -->
            <?php if(isset($aDataFilter['tCate1From']) && !empty($aDataFilter['tCate1From'])) { ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead">หมวดหมู่สินค้าหลัก : </span><?=$aDataFilter['tCate1FromName'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <!-- ============================ ฟิวเตอร์ข้อมูล หมวดหมู่สินค้าย่อย ============================ -->
            <?php if(isset($aDataFilter['tCate2From'])  && !empty($aDataFilter['tCate2From'])){ ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptDisplayBlock" ><span class="xCNRptFilterHead">หมวดหมู่สินค้าย่อย : </span><?=$aDataFilter['tCate2FromName'];?></label>
                    </div>
                </div>
            <?php } ?>
            
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var oFilterLabel = $('.report-filter .text-left label:first-child');
        var nMaxWidth = 0;
        oFilterLabel.each(function(index) {
            var nLabelWidth = $(this).outerWidth();
            if (nLabelWidth > nMaxWidth) {
                nMaxWidth = nLabelWidth;
            }
        });
        $('.report-filter .text-left label:first-child').width(nMaxWidth + 50);
    });
</script>