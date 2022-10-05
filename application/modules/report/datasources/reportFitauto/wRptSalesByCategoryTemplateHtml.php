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
                        <label class="xCNRptDataPrint"><?=$aDataTextRef['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $aDataTextRef['tTimePrint'] . ' ' . date('H:i:s'); ?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%"><?=language('report/report/report', 'tRowNumber'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?=language('report/report/report', 'สาขา'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%"><?=language('report/report/report', 'Lube'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%"><?=language('report/report/report', 'Tire'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%"><?=language('report/report/report', 'Service'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%"><?=language('report/report/report', 'Spare'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <tr >
                                    <td class='xCNRptDetail text-left'><?=@$i+=1?></td>
                                    <td class='xCNRptDetail text-left'><?=($aValue['FTBchName'] == '') ? '-' : $aValue['FTBchName']?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FNPdtGroupLube'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FNPdtGroupTire'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FNPdtGroupService'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FNPdtGroupOther'], $nOptDecimalShow)?></td>
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
                                    <td class='xCNRptSumFooter text-left' colspan='2'>รวมทั้งสิ้น</td>
                                    <td class='xCNRptSumFooter text-right'><?=number_format($aValue['FNPdtGroupLube_Footer'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptSumFooter text-right'><?=number_format($aValue['FNPdtGroupTire_Footer'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptSumFooter text-right'><?=number_format($aValue['FNPdtGroupService_Footer'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptSumFooter text-right'><?=number_format($aValue['FNPdtGroupOther_Footer'], $nOptDecimalShow)?></td>
                                <tr>
                            <?php } ?>
                            
                        <?php else : ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?=$aDataTextRef['tRptAdjStkNoData']; ?></td>
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
