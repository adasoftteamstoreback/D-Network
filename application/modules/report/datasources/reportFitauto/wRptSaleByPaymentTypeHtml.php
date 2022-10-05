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
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRPC15TBBchCode'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRPC15TBBchName'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSalePDTByDayType'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRPC2TBCardType'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptCreditAndCouponType'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPoByBchByPdtDocDate'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptIncomeNotReturnCardPos'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSBPBillNo'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptTaxSalePosByDateFullPayment'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRPCCstForCastBlueCard'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php $tPreviousFmtCode = "";
                            $cSumFmt = 0; ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <tr>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTBchCode'] ?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTBchName'] ?></td>

                                    <!-- <?php if ($tPreviousFmtCode != $aValue['FTFmtCode']) {
                                                $tPreviousFmtCode = $aValue['FTFmtCode']; ?>
                                        <td nowrap class="xCNRptDetail text-center" rowspan='<?= $aValue['FNFmtMaxPageRow'] ?>' style="vertical-align: middle;"><?= $aValue['FTFmtName'] ?></td>
                                    <?php } ?> -->

                                    <td nowrap class="xCNRptDetail text-left"><?= $aValue['FTRcvName'] ?></td>
                                    <?php if ($aValue['FTRcvName']  == 'เงินสด') { ?>
                                        <td nowrap class="xCNRptDetail text-left"> - </td>
                                    <?php } else { ?>
                                        <td nowrap class="xCNRptDetail text-left"><?= $aValue['FTFmtName'] ?></td>
                                    <?php } ?>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTXrcRefNo1'] ?></td>
                                    <td nowrap class="xCNRptDetail"><?= date('d/m/Y', strtotime($aValue['FDCreateOn'])); ?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTPosCode'] ?></td>
                                    <td nowrap class="xCNRptDetail text-left"><?= $aValue['FTXshDocNo'] ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXrcNet'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail"><?= $aValue['FTTxnCrdCode'] ?></td>
                                </tr>

                                <?php if ($aValue['FNFmtAllRow'] == $aValue['FNFmtEndRow']) {  // เช็ค  Group By RCV ?>
                                    <tr>
                                        <td nowrap class="xCNRptDetail"></td>
                                        <td nowrap class="xCNRptDetail" colspan="3"><strong><?php echo language('report/report/report', 'tRptRentAmtForDetailSumText'); ?> : <?= $aValue['FTRcvName'] ?> <?= number_format($aValue['FNFmtEndRow'], 0) ?> <?php echo language('report/report/report', 'tRPCTBFooterList'); ?><strong></td>
                                        <td nowrap class="xCNRptDetail" colspan="4"></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCSumSumRC'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail"></td>
                                    </tr>
                                    <tr class="text-left xCNRptDetail" style=" border-left: 17px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                                <?php } ?>

                                <?php if($aValue['FNFmtPageRow'] == $aValue['FNFmtMaxPageRow']) {  // เช็ค  Group By Branch ?>
                                    <tr class="text-left xCNRptDetail" style=" border-left: 17px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                                    <tr>
                                        <td nowrap class="xCNRptDetail" colspan="4">
                                            <strong>
                                                <?php echo language('report/report/report', 'tRptRentAmtForDetailSumText'); ?> : 
                                                <?= $aValue['FTBchName'] ?>
                                            </strong>
                                        </td>
                                        <td nowrap class="xCNRptDetail" colspan="4"></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['SumSubFooter'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail"></td>
                                    </tr>
                                    <tr class="text-left xCNRptDetail" style=" border-left: 17px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                                <?php } ?>

                            <?php } ?>
                            <?php $nPageNo  = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage     = $aDataReport["aPagination"]["nTotalPage"];
                            if ($nPageNo == $nTotalPage) { ?>
                                <tr>
                                    <td nowrap class="xCNRptDetail"><strong><?php echo language('report/report/report', 'tRptTotalSub'); ?> : <?php echo number_format($aDataReport['aRptData'][0]['RowID_Footer'], 0); ?> <?php echo language('report/report/report', 'tRPCTBFooterList'); ?><strong></td>
                                    <td nowrap class="xCNRptDetail" colspan="3"></td>
                                    <td nowrap class="xCNRptDetail" colspan="4"></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXidQty_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail"></td>
                                </tr>
                                <tr class="text-left xCNRptDetail" style=" border-left: 17px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
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

        </div>
    </div>
</div>

<script>
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