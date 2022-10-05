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
                            <th nowrap class="text-left  xCNRptColumnHeader" colspan="2"><?php echo language('report/report/report', 'tRptSRCBch'); ?></th> 
                            <!-- <th nowrap class="text-left  xCNRptColumnHeader"></th> -->
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptXshDocNo'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptDateDocument'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptinventorytranferOut'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptinventorytranferIn'); ?></th>
                            <th nowrap class="text-center  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptRPDApprov'); ?></th>
                            <!-- <th nowrap class="xCNRptColumnHeader" colspan="1"></th> -->
                        </tr>
                        <tr>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="padding-left: 30px !important;"><?php echo language('report/report/report', 'tRptPdtCode'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPdtName'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptUnit'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptBarCode'); ?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptinventorytranferQTY'); ?></th>
                            <!-- <th nowrap class="xCNRptColumnHeader" colspan="1"></th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php $tPreviousFmtCode = "";
                            $cSumFmt = 0; ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>


                                <?php if ($aValue['FNFmtAllRowDoc'] == 1) { ?>
                                    <tr>
                                        <td nowrap class="xCNRptDetail text-left" colspan="2"><strong><?= $aValue['FTBchCode'] . ' - ' . $aValue['FTBchName'] ?></strong></td>
                                        <!-- <td nowrap class="xCNRptDetail text-left"></td> -->
                                        <td nowrap class="xCNRptDetail text-left"><strong><?= $aValue['FTXthDocNo']; ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-left"><strong><?= date("d/m/Y", strtotime($aValue['FDXthDocDate'])); ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-left"><strong><?= $aValue['FTXthWhFrm'] . ' - ' . $aValue['FTWahNameFrm'] ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-left"><strong><?= $aValue['FTXthWhTo'] . ' - ' . $aValue['FTWahNameTo'] ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-center"><strong><?= $aValue['FTUsrName']; ?></strong></td>
                                    </tr>
                                <?php } ?>




                                <!-- <tr class="text-left xCNRptDetail" style=" border-left: 11px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr> -->
                                <tr>
                                    <td nowrap class="xCNRptDetail text-left" style="padding-left: 30px !important;"><?= $aValue['FTPdtCode']; ?></td>
                                    <td nowrap class="xCNRptDetail text-left"><?= $aValue['FTXtdPdtName']; ?></td>
                                    <!-- <td nowrap class="xCNRptDetail" colspan="2"></td> -->
                                    <td nowrap class="xCNRptDetail text-left"></td>
                                    <td nowrap class="xCNRptDetail text-left"></td>
                                    <td nowrap class="xCNRptDetail text-left"><?= $aValue['FTPunName']; ?></td>
                                    <td nowrap class="xCNRptDetail text-left"><?= $aValue['FTXtdBarCode']; ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXtdQty'], $nOptDecimalShow); ?></td>

                                </tr>


                                <?php if ($aValue['FNFmtAllRowDoc'] == $aValue['FNFmtEndRow']) { ?>
                                    <tr class="text-left xCNRptDetail" style=" border-left: 11px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                                <?php } ?>

                            <?php } ?>
                            <?php $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                            if ($nPageNo == $nTotalPage) { ?>
                                <?php
                                // $total = $aDataReport['aRptData'][0]['FCXshGrand_Footer'] / $aDataReport['aRptData'][0]['FCXshQty_Footer'];
                                ?>
                                <!-- <tr>
                                    <td nowrap class="xCNRptDetail"><strong><?php echo language('report/report/report', 'tRptStkcountvarianceSumFooter'); ?><strong></td>
                                    <td nowrap class="xCNRptDetail" colspan="2"></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshQty_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshPercentByQty_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshTotal_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshPercentByTotal_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshDisChg_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshGrand_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshPercentByGrand_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($total, $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail"></td>
                                </tr>
                                <tr class="text-left xCNRptDetail" style=" border-left: 11px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr> -->
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

            <?php if ((isset($aDataFilter['tPdtNameFrom']) && !empty($aDataFilter['tPdtNameFrom'])) && (isset($aDataFilter['tPdtNameTo']) && !empty($aDataFilter['tPdtNameTo']))) : ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล สินค้า ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeFrom'] . ' : </span>' . $aDataFilter['tPdtNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeTo'] . ' : </span>' . $aDataFilter['tPdtNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ((isset($aDataFilter['tWahCodeFromOut']) && !empty($aDataFilter['tWahCodeFromOut']))) : ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล คลังที่โอน ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo 'คลังโอน' . ' : </span>' . $aDataFilter['tWahNameFromOut']; ?></label>
                        
                    </div>
                </div>
            <?php endif; ?>

            <?php if ((isset($aDataFilter['tWahCodeFromIn']) && !empty($aDataFilter['tWahCodeFromIn']))) : ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล คลังที่รับโอน ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo 'คลังรับ'  . ' : </span>' . $aDataFilter['tWahNameFromIn']; ?></label>
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