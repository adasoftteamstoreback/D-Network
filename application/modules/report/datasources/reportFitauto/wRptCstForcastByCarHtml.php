<?php
$aDataReport    = $aDataViewRpt['aDataReport'];
$aDataTextRef   = $aDataViewRpt['aDataTextRef'];
$aDataFilter    = $aDataViewRpt['aDataFilter'];
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
        border-bottom: dashed 1px #333 !important;
    }

    /** แนวตั้ง */
    @media print {
        @page {
            size: landscape
        }
    }
</style>
<div id="odvRptAdjustStockVendingHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <div class="row">
                <?php include 'application\modules\report\datasources\Address\wRptAddress.php'; ?>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center">
                                <label class="xCNRptTitle"><?php echo @$aDataTextRef['tTitleReport']; ?></label>
                            </div>
                        </div>
                    </div>

                    <?php if ((isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom'] ?> : </label> <label><?= $aDataFilter['tBchNameFrom']; ?></label>&nbsp;
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchTo'] ?> : </label> <label><?= $aDataFilter['tBchNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php }; ?>

                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่ ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateFrom'] ?> : </label> <label><?= date('d/m/Y', strtotime($aDataFilter['tDocDateFrom'])); ?></label>&nbsp;
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateTo'] ?> : </label> <label><?= date('d/m/Y', strtotime($aDataFilter['tDocDateTo'])); ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 "></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 table-responsive">
                    <div class="text-right">
                        <label class="xCNRptDataPrint"><?php echo $aDataTextRef['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $aDataTextRef['tTimePrint'] . ' ' . date('H:i:s'); ?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr style="border-bottom: 1px solid black !important">
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRPCCstForCastNextCycle'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:15%;"><?php echo language('report/report/report', 'tRPCCstForCastName') . ' - ' . language('report/report/report', 'tRPCCstForCastLName'); ?></th>
                            <!-- <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRPCCstForCastLName'); ?></th> -->
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRPCCstForCastTel'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRPCCstForCastAddess'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRPCCstForCastEmail'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:8%;"><?php echo language('report/report/report', 'tRPCCstForCastVehicleReg'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRPCCstForCastLastSer'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:8%;"><?php echo language('report/report/report', 'tRptCstLostContSatisfact'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRPCCstForCastLastBch'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php
                            $nSubSumQty = 0;
                            $nSumQtyTotal = 0;
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <?php
                                if ($aValue["FNRptRowSeq"] == 1) {
                                    $dDate = date('d/m/Y', strtotime($aValue['FDFlwDateForcast']));
                                    $tClassTop = "border-top: 1px solid black !important";
                                } else {
                                    $dDate = '';
                                    $tClassTop = "";
                                }
                                // $dDate = date('d/m/Y', strtotime($aValue['FDFlwDateForcast']));
                                ?>
                                <tr style="<?= $tClassTop ?>">
                                    <td class="text-left xCNRptDetail"><?php echo $dDate ?></td>
                                    <td class="text-left xCNRptDetail"><?php echo $aValue["FTXshCstName"]; ?></td>
                                    <!-- <td class="text-left xCNRptDetail"><?php echo $aValue["FTXshCstName"]; ?></td> -->
                                    <td class="text-left xCNRptDetail"><?php echo ($aValue["FTCstTel"] == '') ? '-' : $aValue["FTCstTel"]; ?></td>
                                    <td class="text-left xCNRptDetail"><?php echo ($aValue["FTCstAddress"] == '') ? '-' : $aValue["FTCstAddress"]; ?></td>
                                    <td class="text-left xCNRptDetail"><?php echo ($aValue["FTCstEmail"] == '') ? '-' : $aValue["FTCstEmail"]; ?></td>
                                    <td class="text-left xCNRptDetail"><?php echo ($aValue["FTCarRegNo"] == '') ? '-' : $aValue["FTCarRegNo"]; ?></td>
                                    <td class="text-left xCNRptDetail"><?php echo date('d/m/Y', strtotime($aValue["FDFlwLastDate"])); ?></td>
                                    <td class="text-left xCNRptDetail"><?php echo ($aValue["FNXshScoreValue"] == '') ? '-' : $aValue["FNXshScoreValue"]; ?></td>
                                    <td class="text-left xCNRptDetail"><?php echo $aValue["FTBchName"]; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="xCNTrFooter">
                                <td colspan="2" class="text-left xCNRptSumFooter"><?= language('report/report/report', 'tRptCstLostContCountSum');
                                                                                    echo $aDataReport['aRptData'][0]["Cst_Total"];
                                                                                    echo ' ' . language('report/report/report', 'tRptCstLostContCountBack'); ?></td>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?= $aDataTextRef['tRptTaxSalePosNoData']; ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ((isset($aDataFilter['tMerCodeFrom']) && !empty($aDataFilter['tMerCodeFrom'])) && (isset($aDataFilter['tMerCodeTo']) && !empty($aDataFilter['tMerCodeTo']))
                || (isset($aDataFilter['tShpCodeFrom']) && !empty($aDataFilter['tShpCodeFrom'])) && (isset($aDataFilter['tShpCodeTo']) && !empty($aDataFilter['tShpCodeTo']))
                || (isset($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeFrom'])) && (isset($aDataFilter['tPosCodeTo']) && !empty($aDataFilter['tPosCodeTo']))
                || (isset($aDataFilter['tWahCodeFrom']) && !empty($aDataFilter['tWahCodeFrom'])) && (isset($aDataFilter['tWahCodeTo']) && !empty($aDataFilter['tWahCodeTo']))
                || (isset($aDataFilter['tBchCodeSelect']))
                || (isset($aDataFilter['tMerCodeSelect']))
                || (isset($aDataFilter['tShpCodeSelect']))
                || (isset($aDataFilter['tPosCodeSelect']))
                || (isset($aDataFilter['tWahCodeSelect']))
            ) { ?>


                <div class="xCNRptFilterTitle">
                    <div class="text-left">
                        <label class="xCNTextConsOth"><?= $aDataTextRef['tRptConditionInReport']; ?></label>
                    </div>
                </div>
            <?php }; ?>


            <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
            <?php if (isset($aDataFilter['tBchCodeSelect']) && !empty($aDataFilter['tBchCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['bBchStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tBchNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>


            <?php if ((isset($aDataFilter['tCstCodeFrom']) && !empty($aDataFilter['tCstCodeFrom'])) && (isset($aDataFilter['tCstCodeTo']) && !empty($aDataFilter['tCstCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ลูกค้า =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom'] . ' : </span>' . $aDataFilter['tCstCodeFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstTo'] . ' : </span>' . $aDataFilter['tCstCodeTo']; ?></label>
                    </div>
                </div>
            <?php }; ?>

            <?php if ((isset($aDataFilter['tRegCodeFrom']) && !empty($aDataFilter['tRegCodeFrom'])) && (isset($aDataFilter['tRegCodeTo']) && !empty($aDataFilter['tRegCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ทะเบียนรถ =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptRegCodeFrom'] . ' : </span>' . $aDataFilter['tRegCodeFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptRegCodeTo'] . ' : </span>' . $aDataFilter['tRegCodeTo']; ?></label>
                    </div>
                </div>
            <?php }; ?>

        </div>
        <div class="xCNFooterPageRpt">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <?php if ($aDataReport["aPagination"]["nTotalPage"] > 0) : ?>
                            <label class="xCNRptLabel"><?php echo $aDataReport["aPagination"]["nDisplayPage"] . ' / ' . $aDataReport["aPagination"]["nTotalPage"]; ?></label>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
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