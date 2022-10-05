<?php
    $aDataReport        = $aDataViewRpt['aDataReport'];
    $aDataTextRef       = $aDataViewRpt['aDataTextRef'];
    $aDataFilter        = $aDataViewRpt['aDataFilter'];
    $nOptDecimalShow    = get_cookie('tOptDecimalShow');
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
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptStkAllCprTextFileBchCode');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptStkAllCprTextFileBchPlant');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptStkAllCprTextFileBchName');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptStkAllCprTextFilePdtCode');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:20%;"><?=language('report/report/report','tRptStkAllCprTextFilePdtName');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptStkAllCprTextFilePdtUnit');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;">หมวดหมู่สินค้าหลัก</th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;">หมวดหมู่สินค้าย่อย</th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;">ชื่อหมวดหมู่สินค้าย่อย</th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptStkAllCprTextFilePdtGrp');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptStkAllCprTextFileQty');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptStkAllCprTextFileCost');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:5%;">ต้นทุนรวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])): ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue): ?>
                                <tr>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTBchCode'];?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTBchRefID'];?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTBchName'];?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTPdtCode'];?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTPdtName'];?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTPunName'];?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTPdtCat1'];?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTPdtCat2'];?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTCatName'];?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTMapUsrValue'];?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXtdQty'],$nOptDecimalShow); ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXtdCost'],$nOptDecimalShow); ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXtdAmount'],$nOptDecimalShow); ?></td>
                                </tr>

                                <?php if($aValue['rtPartitionBCH'] == $aValue['rtPartitionCountBCH']) {  // เช็ค  Group By Branch ?>
                                    <tr class="text-left xCNRptDetail" style=" border-left: 17px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                                    <tr>
                                        <td nowrap class="xCNRptDetail" colspan="10">
                                            <strong><?php echo language('report/report/report', 'tRptRentAmtForDetailSumText'); ?> : <?=$aValue['FTBchName']?></strong>
                                        </td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXtdQty_SumBch'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXtdCost_SumBch'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXtdAmount_SumBch'], $nOptDecimalShow) ?></strong></td>
                                    </tr>
                                    <tr class="text-left xCNRptDetail" style=" border-left: 17px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                                <?php }?>
                            <?php endforeach;?>

                            <?php 
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                            ?>
                            <?php if ($nPageNo == $nTotalPage): ?>
                                <tr>
                                    <td nowrap class="xCNRptDetail"><strong><?php echo language('report/report/report', 'tRptTotalSub'); ?> : <?php echo number_format($aDataReport['aRptData'][0]['RowID_Footer'], 0); ?> <?php echo language('report/report/report', 'tRPCTBFooterList'); ?><strong></td>
                                    <td nowrap class="xCNRptDetail" colspan="9"></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXtdQty_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXtdCost_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXtdAmount_Footer'], $nOptDecimalShow) ?></strong></td>
                                </tr>
                            <?php endif; ?>

                        <?php else : ?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?= $aDataTextRef['tRptNoData']; ?></td></tr>
                        <?php endif;?>
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

            <?php if ((isset($aDataFilter['tPdtCodeFrom']) && !empty($aDataFilter['tPdtCodeFrom'])) && (isset($aDataFilter['tPdtCodeTo']) && !empty($aDataFilter['tPdtCodeTo']))) : ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล สินค้า ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeFrom']; ?> : </span> <?php echo $aDataFilter['tPdtNameFrom']; ?></label>
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeTo']; ?> : </span> <?php echo $aDataFilter['tPdtNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ((isset($aDataFilter['tPdtUnitCodeFrom']) && !empty($aDataFilter['tPdtUnitCodeFrom'])) && (isset($aDataFilter['tPdtUnitCodeTo']) && !empty($aDataFilter['tPdtUnitCodeTo']))) : ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล หน่วยสินค้า ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptPdtUnitFrom']; ?> : </span> <?php echo $aDataFilter['tPdtUnitNameFrom']; ?></label>
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptPdtUnitTo']; ?> : </span> <?php echo $aDataFilter['tPdtUnitNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($aDataFilter['tCate1CodeFrom']) && !empty($aDataFilter['tCate1CodeFrom'])) : ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล หมวดหมู่สินค้าหลัก ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCat1']; ?> : </span> <?php echo $aDataFilter['tCate1NameFrom']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($aDataFilter['tCate2CodeFrom']) && !empty($aDataFilter['tCate2CodeFrom'])) : ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล หมวดหมู่สินค้าย่อย ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCat2']; ?> : </span> <?php echo $aDataFilter['tCate2NameFrom']; ?></label>
                    </div>
                </div>
            <?php endif; ?>
        
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