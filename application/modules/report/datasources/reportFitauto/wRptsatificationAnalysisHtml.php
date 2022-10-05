<?php
$aDataReport    = $aDataViewRpt['aDataReport'];
$aDataTextRef   = $aDataViewRpt['aDataTextRef'];
$aDataFilter    = $aDataViewRpt['aDataFilter'];
?>
<style>
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
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjDateFrom'] ?> : </label> <label><?= date('d/m/Y', strtotime($aDataFilter['tDocDateFrom'])); ?></label>&nbsp;
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjDateTo'] ?> : </label> <label><?= date('d/m/Y', strtotime($aDataFilter['tDocDateTo'])); ?></label>
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
                        <tr>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSatificationTopic'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSatificationList'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSatification5score'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSatification4score'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSatification3score'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSatification2score'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSatification1score'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSatificationAvg'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSatificationStandard'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php
                            $nSubSumQty = 0;
                            $nSumQtyTotal = 0;
                            $nGrp = '';
                            $nSeqRows = 0;
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <?php if($aValue['FNQahGroup'] != $nGrp) {
                                    $nSeqRows = 0;
                                } ?>
                                    <?php if($aValue['FNQadSeqNo'] != $nSeqRows){ ?>
                                    <!--  Step 2 แสดงข้อมูลใน TD  -->
                                    <tr class="xWSattr xWSatLng<?=$aValue['FNQahGroup']?>" data-grpno='<?=$aValue['FNQahGroup']?>' style="border-top: 1px solid #ddd !important;">
                                    <?php if ($nSeqRows == 0) { ?>
                                        <td class="text-left xCNRptDetail xWSattd<?=$aValue['FNQahGroup']?>"width="25%" style="vertical-align: inherit;"><?php echo $aValue["FTQgpName"]; ?></td>
                                    <?php } ?>
                                        <td class="text-left xCNRptDetail" width="35%"><?php echo $aValue["FTQadName"]; ?></td>
                                        <td class="text-center xCNRptDetail" width="5%"><?php echo number_format($aValue["FNScoValue5"], $nOptDecimalShow); ?></td>
                                        <td class="text-center xCNRptDetail" width="5%"><?php echo number_format($aValue["FNScoValue4"], $nOptDecimalShow); ?></td>
                                        <td class="text-center xCNRptDetail" width="5%"><?php echo number_format($aValue["FNScoValue3"], $nOptDecimalShow); ?></td>
                                        <td class="text-center xCNRptDetail" width="5%"><?php echo number_format($aValue["FNScoValue2"], $nOptDecimalShow); ?></td>
                                        <td class="text-center xCNRptDetail" width="5%"><?php echo number_format($aValue["FNScoValue1"], $nOptDecimalShow); ?></td>
                                        <td class="text-center xCNRptDetail" width="5%"><?php echo $aValue["FCScoAvg"];?></td>
                                        <td class="text-left xCNRptDetail" width="10%">
                                            <?php
                                                $nScoAvg = $aValue["FCScoAvg"];
                                                $tCrt = '';

                                                if ($nScoAvg >= 1.00 && $nScoAvg <= 1.80) {
                                                    $tCrt = "tRptSatification1score";
                                                }elseif ($nScoAvg >= 1.81 && $nScoAvg <= 2.60) {
                                                    $tCrt = "tRptSatification2score";
                                                }elseif ($nScoAvg >= 2.61 && $nScoAvg <= 3.40) {
                                                    $tCrt = "tRptSatification3score";
                                                }elseif ($nScoAvg >= 3.41 && $nScoAvg <= 4.20) {
                                                    $tCrt = "tRptSatification4score";
                                                }elseif ($nScoAvg >= 4.21 && $nScoAvg <= 5) {
                                                    $tCrt = "tRptSatification5score";
                                                }else{
                                                    $tCrt = '-';
                                                }
                                                echo language('report/report/report', $tCrt);
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php
                                    $nSeqRows = $aValue['FNQadSeqNo'];
                                    $nGrp = $aValue['FNQahGroup'];
                                ?>
                            <?php endforeach; ?>
                            <?php
                                $aDataTotal = $aDataReport['aRptData'][0];
                                $nScoAvgTotal = $aDataTotal["FCScoAvgTotal"] / $aDataTotal["FCScoAvgCount"];
                                $nScoAvg = $nScoAvgTotal;
                                $tCrt = '';

                                if ($nScoAvg >= 1.00 && $nScoAvg <= 1.80) {
                                    $tCrt = "tRptSatification1score";
                                }elseif ($nScoAvg >= 1.81 && $nScoAvg <= 2.60) {
                                    $tCrt = "tRptSatification2score";
                                }elseif ($nScoAvg >= 2.61 && $nScoAvg <= 3.40) {
                                    $tCrt = "tRptSatification3score";
                                }elseif ($nScoAvg >= 3.41 && $nScoAvg <= 4.20) {
                                    $tCrt = "tRptSatification4score";
                                }elseif ($nScoAvg >= 4.21 && $nScoAvg <= 5) {
                                    $tCrt = "tRptSatification5score";
                                }else{
                                    $tCrt = '-';
                                }

                            //Step 6 : สั่ง Summary Footer
                            $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];

                            ?>
                            <?php if ($nPageNo == $nTotalPage) { ?>
                                <tr class='xCNTrFooter'>
                                    <td class='xCNRptSumFooter text-center' colspan='2'>รวม</td>
                                    <td class='xCNRptSumFooter text-center'><?php echo number_format($aDataTotal['FNScoValue5Total'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptSumFooter text-center'><?php echo number_format($aDataTotal['FNScoValue4Total'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptSumFooter text-center'><?php echo number_format($aDataTotal['FNScoValue3Total'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptSumFooter text-center'><?php echo number_format($aDataTotal['FNScoValue2Total'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptSumFooter text-center'><?php echo number_format($aDataTotal['FNScoValue1Total'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptSumFooter text-center'><?php echo number_format($nScoAvgTotal, $nOptDecimalShow)?></td>
                                    <td class='xCNRptSumFooter text-left'><?php echo language('report/report/report', $tCrt)?></td>
                                <tr>
                            <?php } ?>

                        <?php else : ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?php echo $aDataTextRef['tRptAdjStkNoData']; ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="row" >
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <div class="xCNRptFilterTitle">
                        <div class="text-left">
                            <label class="xCNTextConsOth"><?= $aDataTextRef['tRptConditionInReport']; ?></label>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <label class="text-left xCNRptDetail"><span style="color:red;"><?php echo language('report/report/report', 'tRptSatificationStandard2')?></span> 5 <?php echo language('report/report/report', 'tRptSatificationLevel')?></label><br>
                    <label class="text-left xCNRptDetail"><span style="color:red;"><?php echo language('report/report/report', 'tRptSatificationSatisfied')?></span> <?php echo language('report/report/report', 'tRptSatificationAvg5score')?> <?php echo language('report/report/report', 'tRptSatificationAvg')?> 4.21 - 5.00</label><br>
                    <label class="text-left xCNRptDetail"><span style="color:red;"><?php echo language('report/report/report', 'tRptSatificationSatisfied')?></span> <?php echo language('report/report/report', 'tRptSatificationAvg4score')?> <?php echo language('report/report/report', 'tRptSatificationAvg')?> 3.41 - 4.20</label><br>
                    <label class="text-left xCNRptDetail"><span style="color:red;"><?php echo language('report/report/report', 'tRptSatificationSatisfied')?></span> <?php echo language('report/report/report', 'tRptSatificationAvg3score')?> <?php echo language('report/report/report', 'tRptSatificationAvg')?> 2.61 - 3.40</label><br>
                    <label class="text-left xCNRptDetail"><span style="color:red;"><?php echo language('report/report/report', 'tRptSatificationSatisfied')?></span> <?php echo language('report/report/report', 'tRptSatificationAvg2score')?> <?php echo language('report/report/report', 'tRptSatificationAvg')?> 1.81 - 2.60</label><br>
                    <label class="text-left xCNRptDetail"><span style="color:red;"><?php echo language('report/report/report', 'tRptSatificationSatisfied')?></span> <?php echo language('report/report/report', 'tRptSatificationAvg1score')?> <?php echo language('report/report/report', 'tRptSatificationAvg')?> 1.00 - 1.80</label><br>
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
    $("document").ready(function() {
        JSxSatSetRowSpan();
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

    function JSxSatSetRowSpan(){
        $('.xWSattr').each(function(){
            var tDataSatGrp        = $(this).data('grpno');
            var nContDataRowSpan    = $('.xWSatLng'+tDataSatGrp).length;
            $('.xWSattd'+tDataSatGrp).attr('rowspan',nContDataRowSpan);
        });
    }
</script>
