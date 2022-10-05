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
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptWhtBch');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptWhtCstCode');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:20%;"><?=language('report/report/report','tRptWhtCstName');?></th> 
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptWhtDocDate');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptWhtDocNo');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptWhtVat');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptWhtBFVat');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptWhtGrand');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','เลขที่เอกสารอ้างอิง');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','เอกสารหักภาษี ณ ที่จ่าย');?></th> 
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','ยอดหัก ณ ที่จ่าย');?></th>
                            <!-- <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptWhtRcvName');?></th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])): ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue): ?>
                                <tr>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTBchName'] ?></td>
                                    <td nowrap class="xCNRptDetail text-left"><?= (!empty($aValue['FTCstCode']))? $aValue['FTCstCode'] : '-'; ?></td>
                                    <td nowrap class="xCNRptDetail text-left"><?= (!empty($aValue['FTCstCode']))? $aValue['FTCstName'] : '-'; ?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FDXshDocDate']; ?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTXshDocNo']; ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshVat'],$nOptDecimalShow); ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshVatable'],$nOptDecimalShow); ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshGrand'],$nOptDecimalShow); ?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTXrcRefNo1']; ?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTXshDocNoRef']; ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXrcNet'],$nOptDecimalShow); ?></td>
                                    <!-- <td nowrap class="xCNRptDetail"><?= $aValue['FTRcvName']; ?></td> -->
                                </tr>
                                

                                <?php if($aValue['FNFmtPageRow'] == $aValue['FNFmtMaxPageRow']) {  // เช็ค  Group By Branch ?>
                                     <tr class="text-left xCNRptDetail" style=" border-left: ถpx dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                                    <tr>
                                        <td nowrap class="xCNRptDetail" colspan="5">
                                        <strong> <?php echo language('report/report/report', 'tRptRentAmtForDetailSumText'); ?> : <?=$aValue['FTBchName']. ' / ' .  $aValue['FNFmtEndRow'] . ' ' . 'รายการ';?></strong>
                                        </td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXshVatSumBch'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXshVatableSumBch'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXshGrandSumBch'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail"></td>
                                        <td nowrap class="xCNRptDetail"></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXrcNetSumBch'], $nOptDecimalShow) ?></strong></td>
                                        <!-- <td nowrap class="xCNRptDetail"></td> -->
                                    </tr>
                                    <tr class="text-left xCNRptDetail" style=" border-left: 5px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                                <?php }?>




                            <?php endforeach;?>
                            <?php 
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                            ?>
                            <?php if ($nPageNo == $nTotalPage): ?>
                                <tr>
                                    <td nowrap class="xCNRptDetail"><strong><?php echo language('report/report/report', 'tRptTotalSub'); ?> : <?php echo number_format($aDataReport['aRptData'][0]['RowID_Footer'], 0); ?> <?php echo language('report/report/report', 'tRPCTBFooterList'); ?><strong></td>
                                    <td nowrap class="xCNRptDetail" colspan="4"></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshVat_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshVatable_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshGrand_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail"></td>
                                    <td nowrap class="xCNRptDetail"></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXrcNet_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <!-- <td nowrap class="xCNRptDetail"></td> -->
                                </tr>
                                <tr class="text-left xCNRptDetail" style=" border-left: 17px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                            <?php endif; ?>
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
            <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
            <?php if (isset($aDataFilter['tBchCodeSelect']) && !empty($aDataFilter['tBchCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['bBchStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tBchNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ((isset($aDataFilter['tRcvCodeFrom']) && !empty($aDataFilter['tRcvCodeFrom'])) && (isset($aDataFilter['tRcvCodeTo']) && !empty($aDataFilter['tRcvCodeTo']))) : ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล ประเภทการชำระ ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSaleByPaymentDetailFilterPayTypeFrom']; ?> : </span> <?php echo $aDataFilter['tRcvNameFrom']; ?></label>
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSaleByPaymentDetailFilterPayTypeTo']; ?> : </span> <?php echo $aDataFilter['tRcvNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <?php if( (isset($aDataFilter['tCstCodeFrom']) && !empty($aDataFilter['tCstCodeFrom'])) && (isset($aDataFilter['tCstCodeTo']) && !empty($aDataFilter['tCstCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ลูกค้า =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom'].' : </span>'.$aDataFilter['tCstCodeFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstTo'].' : </span>'.$aDataFilter['tCstCodeTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

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