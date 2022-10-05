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
                            <th nowrap class="text-center  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRowNumber'); ?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptaveragedaytoweeksalesDayWeek'); ?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptInventoriesByBchPrice'); ?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptaveragedaytoweeksalesQTYSales'); ?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader">% / <?php echo language('report/report/report', 'tRptaveragedaytoweeksalesQTYSales'); ?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptaveragedaytoweeksalesGoleSale'); ?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader">% / <?php echo language('report/report/report', 'tRptaveragedaytoweeksalesGoleSale'); ?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptDisChg'); ?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptaveragedaytoweeksalesGoleSaleTotal'); ?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader">% / <?php echo language('report/report/report', 'tRptaveragedaytoweeksalesGoleSaleTotal'); ?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptaveragedaytoweeksalesAvgrageSalesPerPrice'); ?></th>
                            <th nowrap class="xCNRptColumnHeader" colspan="1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php $tPreviousFmtCode = "";
                            $cSumFmt = 0; ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>

                                <?php
                                $tDay = '';
                                if ($aValue['FTXshDayOfWeek'] == 1) {
                                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesMonday');
                                } else if ($aValue['FTXshDayOfWeek'] == 2) {
                                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesTuesday');
                                } else if ($aValue['FTXshDayOfWeek'] == 3) {
                                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesWednesday');
                                } else if ($aValue['FTXshDayOfWeek'] == 4) {
                                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesThursday');;
                                } else if ($aValue['FTXshDayOfWeek'] == 5) {
                                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesFriday');
                                } else if ($aValue['FTXshDayOfWeek'] == 6) {
                                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesSaturday');
                                } else if ($aValue['FTXshDayOfWeek'] == 7) {
                                    $tDay = language('report/report/report', 'tRptaveragedaytoweeksalesSunday');
                                }
                                ?>
                                <?php
                                $tSubbyName =  $aValue['FTSubByName'];
                                if ($aValue['FTSubByName'] == NULL || $aValue['FTSubByName'] == '') {
                                    $tSubbyName =  'N/A';
                                } ?>

                                <?php if ($aValue['FNFmtAllRow'] == 1) { ?>
                                    <tr>
                                        <td nowrap class="xCNRptDetail" colspan="12"><strong><?php echo language('report/report/report', 'tRptTaxSalePosBch'); ?> (<?php echo $aValue['FTBchCode']; ?>) <?php echo $aValue['FTBchName']; ?> </strong></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($aValue['SumSub'] == 1) { ?>
                                    <tr>
                                        <td nowrap class="xCNRptDetail" colspan="12" style="padding-left: 30px !important;"><strong><?php echo $tSubbyName; ?></strong></td>
                                    </tr>
                                <?php } ?>

                                <tr>
                                    <td nowrap class="xCNRptDetail text-center"><?= $aValue['SumSub'] ?></td>
                                    <td nowrap class="xCNRptDetail text-left"><?= $tDay; ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshUnitPrice'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshQty'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshPercentByQty'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshTotal'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshPercentByTotal'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshDisChg'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshGrand'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshPercentByGrand'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="xCNRptDetail text-right"><?= number_format($aValue['FCXshSalAvgByQty'], $nOptDecimalShow) ?></td>
                                </tr>

                                <?php if ($aValue['SumSub'] == $aValue['SumSubEndRow']) { ?>

                                    <tr class="text-left xCNRptDetail" style=" border-left: 11px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                                    <tr>
                                        <td nowrap class="xCNRptDetail" style="padding-left: 30px !important;"><strong><?php echo language('report/report/report', 'tRptSalByPaymentSum'); ?> : <?php echo  $tSubbyName; ?></strong></td>
                                        <td nowrap class="xCNRptDetail" colspan="2"></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXshQty_DocNo_Footer'], $nOptDecimalShow) ?></strong></td>

                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXshPercentByQty_DocNo_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXshTotal_DocNo_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXshPercentByTotal_DocNo_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXshDisChg_DocNo_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXshGrand_DocNo_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXshPercentByGrand_DocNo_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['FCXshSalAvgByQty_DocNo_Footer'], $nOptDecimalShow) ?></strong></td>
                                    </tr>
                                    <tr class="text-left xCNRptDetail" style=" border-left: 11px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>

                                <?php } ?>




                                <!-- <?php if ($aValue['FNFmtAllRow'] == $aValue['FNFmtEndRow']) { ?>
                                    <tr>
                                        <td nowrap class="xCNRptDetail"></td>
                                        <td nowrap class="xCNRptDetail" colspan="3"><strong>รวม : <?= $aValue['FTFmtName'] ?> <?= number_format($aValue['FNFmtEndRow'], 0) ?> รายการ<strong></td>
                                        <td nowrap class="xCNRptDetail" colspan="4"></td>
                                        <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aValue['SumSubFooter'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptDetail"></td>
                                    </tr>
                                    <tr class="text-left xCNRptDetail" style=" border-left: 17px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>

                                <?php } ?> -->

                            <?php } ?>
                            <?php $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                            if ($nPageNo == $nTotalPage) { ?>
                            <?php
                                $total = $aDataReport['aRptData'][0]['FCXshGrand_Footer'] / $aDataReport['aRptData'][0]['FCXshQty_Footer'];
                            ?>
                                <tr>
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
                                <tr class="text-left xCNRptDetail" style=" border-left: 11px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
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

            <?php if ((isset($aDataFilter['tSubByCodeSelect']) && !empty($aDataFilter['tSubByCodeSelect']))) : ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล ข้อมูลสินค้า ============================ -->
                <?php 
                    if($aDataFilter['tSubByCodeSelect'] == 'PdtType'){
                        $tSubBy = language('report/report/report', 'tRptGrpPdtType');
                    }else if($aDataFilter['tSubByCodeSelect'] == 'PdtBrand'){
                        $tSubBy = language('report/report/report', 'tRptGrpPdtBrand');
                    }else if($aDataFilter['tSubByCodeSelect'] == 'PdtModel'){
                        $tSubBy = language('report/report/report', 'tRptGrpPdtModel');
                    }else if($aDataFilter['tSubByCodeSelect'] == 'PdtChain'){
                        $tSubBy = language('report/report/report', 'tRptGrpPdtGroup');
                    }
                ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDetailProduct']; ?> : </span> <?=$tSubBy?></label>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ((isset($aDataFilter['tRptDayOfWeekFrm']) && !empty($aDataFilter['tRptDayOfWeekFrm'])) && (isset($aDataFilter['tRptDayOfWeekTo']) && !empty($aDataFilter['tRptDayOfWeekTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล จ - อ =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <?php
                            if ($aDataFilter['tRptDayOfWeekFrm'] == 1) {
                                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesMonday');
                            } else if ($aDataFilter['tRptDayOfWeekFrm'] == 2) {
                                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesTuesday');
                            } else if ($aDataFilter['tRptDayOfWeekFrm'] == 3) {
                                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesWednesday');
                            } else if ($aDataFilter['tRptDayOfWeekFrm'] == 4) {
                                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesThursday');;
                            } else if ($aDataFilter['tRptDayOfWeekFrm'] == 5) {
                                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesFriday');
                            } else if ($aDataFilter['tRptDayOfWeekFrm'] == 6) {
                                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesSaturday');
                            } else if ($aDataFilter['tRptDayOfWeekFrm'] == 7) {
                                $tDayFrm = language('report/report/report', 'tRptaveragedaytoweeksalesSunday');
                            }

                            if ($aDataFilter['tRptDayOfWeekTo'] == 1) {
                                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesMonday');
                            } else if ($aDataFilter['tRptDayOfWeekTo'] == 2) {
                                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesTuesday');
                            } else if ($aDataFilter['tRptDayOfWeekTo'] == 3) {
                                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesWednesday');
                            } else if ($aDataFilter['tRptDayOfWeekTo'] == 4) {
                                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesThursday');;
                            } else if ($aDataFilter['tRptDayOfWeekTo'] == 5) {
                                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesFriday');
                            } else if ($aDataFilter['tRptDayOfWeekTo'] == 6) {
                                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesSaturday');
                            } else if ($aDataFilter['tRptDayOfWeekTo'] == 7) {
                                $tDayTo = language('report/report/report', 'tRptaveragedaytoweeksalesSunday');
                            }
                        ?>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDate'] . ' : </span>' . $tDayFrm . ' - ' . $tDayTo ?></label>
                    </div>
                </div>
            <?php }; ?>

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