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
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRowNumber'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptXshDocNo'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptCst'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptRecorder'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptRPDApprov'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptCabinetCost'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSales'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptDiff'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader">% <?php echo language('report/report/report', 'tRptDiff'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <tr style="border-top: 1px solid #ddd !important;">
                                    <td class='xCNRptDetail text-left'><?=@$i+=1?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTXshDocNo']?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTCstName']?></td>
                                    <td class='xCNRptDetail text-center'><?=$aValue['FTUsrRec']?></td>
                                    <td class='xCNRptDetail text-center'><?=$aValue['FTUsrAppName']?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdCostEX'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdNetSale'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdDiff'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdPerDiff'], $nOptDecimalShow)?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php $i++; ?>
                            <?php

                            //Summary Footer
                            $aDataTotal = $aDataReport['aRptData'][0];
                            $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];

                            if ($nPageNo == $nTotalPage) { ?>
                                <tr class='xCNTrFooter'>
                                    <td class='xCNRptSumFooter text-center' colspan='5'><?php echo language('report/report/report', 'tRptTotal'); ?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdCostEX_Footer'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdNetSale_Footer'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdDiff_Footer'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdPerDiff_Footer'], $nOptDecimalShow)?></td>
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
    </div>
</div>
