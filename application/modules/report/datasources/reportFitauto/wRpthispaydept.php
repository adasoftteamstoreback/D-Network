<?php
$aDataReport    = $aDataViewRpt['aDataReport'];
$aDataTextRef   = $aDataViewRpt['aDataTextRef'];
$aDataFilter    = $aDataViewRpt['aDataFilter'];
$nOptDecimalShow = FCNxHGetOptionDecimalShow();
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
                            <th nowrap class="text-left xCNRptColumnHeader"><?=language('report/report/report', 'tRptPayDePtCol1'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?=language('report/report/report', 'tRptPayDePtCol2'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?=language('report/report/report', 'tRptPayDePtCol3'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?=language('report/report/report', 'tRptPayDePtCol4'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?=language('report/report/report', 'tRptPayDePtCol5'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader"><?=language('report/report/report', 'tRptPayDePtCol6'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader"><?=language('report/report/report', 'tRptPayDePtCol7'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader"><?=language('report/report/report', 'tRptPayDePtCol8'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <?php if($aValue['rtPartitionSPL'] == 1){ ?>
                                    <tr>
                                        <td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class="text-left xCNRptGrouPing" style="text-indent:22px;" colspan="8">(<?= $aValue['FTSplCode']; ?>) <?= $aValue['FTSplName']; ?></td>
                                    </tr>
                                <?php } ?>

                                <tr>
                                  <td class="text-center xCNRptDetail"></td>
                                  <td class="text-left xCNRptDetail"><?=$aValue['FTXphDocNo']; ?></td>
                                  <td class="text-center xCNRptDetail" ><?=date("d/m/Y", strtotime($aValue['FDXphDocDate'])); ?></td>
                                  <td class="text-center xCNRptDetail" ><?=date("d/m/Y", strtotime($aValue['FDXpdDueDate'])); ?></td>
                                  <td class="text-center xCNRptDetail" ><?=($aValue['FDXpdPayDate'] == '' ) ? '-' : date("d/m/Y", strtotime($aValue['FDXpdPayDate'])); ?></td>
                                  <td class="text-right xCNRptDetail" ><?=number_format($aValue['FCXphGrand'],$nOptDecimalShow); ?></td>
                                  <td class="text-right xCNRptDetail" ><?=number_format($aValue['FCXphPaid'],$nOptDecimalShow); ?></td>
                                  <td class="text-right xCNRptDetail" ><?=number_format($aValue['FCXphLeft'],$nOptDecimalShow); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php 
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                if ($nPageNo == $nTotalPage) { ?>
                                   <tr style='border-top: solid 1px #333 !important;border-bottom: solid 1px #333 !important;' class='xCNTrFooter'>
                                        <td nowrap class="xCNRptGrouPing"><strong><?php echo language('report/report/report', 'tRptRentAmtForDetailSumText'); ?><strong></td>
                                        <td nowrap class="xCNRptGrouPing" colspan="4"></td>
                                        <td nowrap class="xCNRptGrouPing text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXphGrand_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptGrouPing text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXphPaid_Footer'], $nOptDecimalShow) ?></strong></td>
                                        <td nowrap class="xCNRptGrouPing text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXphLeft_Footer'], $nOptDecimalShow) ?></strong></td>
                                    </tr>
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

            <?php if( (isset($aDataFilter['tPdtSupplierCodeFrom']) && !empty($aDataFilter['tPdtSupplierCodeFrom'])) && (isset($aDataFilter['tPdtSupplierCodeTo']) && !empty($aDataFilter['tPdtSupplierCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ผู้จำหน่าย =========================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplFrom'].' : </span>'.$aDataFilter['tPdtSupplierNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTo'].' : </span>'.$aDataFilter['tPdtSupplierNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if((isset($aDataFilter['tPdtRptPhStaPaid']) && !empty($aDataFilter['tPdtRptPhStaPaid']))){ ?>
                <!-- ===== ฟิวเตอร์ข้อมูล สถานะ รับ/จ่ายเงิน =========================================== -->
                <?php 
                    $tStaPaid = '';
                    if ($aDataFilter['tPdtRptPhStaPaid'] == 1) {
                        $tStaPaid = $aDataTextRef['tRptPhStaPaid1'];
                    }elseif ($aDataFilter['tPdtRptPhStaPaid'] == 2) {
                        $tStaPaid = $aDataTextRef['tRptPhStaPaid2'];
                    }elseif ($aDataFilter['tPdtRptPhStaPaid'] == 3) {
                        $tStaPaid = $aDataTextRef['tRptPhStaPaid3'];
                    }
                ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tStaPaid'].' : </span>'.$tStaPaid;?></label>
                    </div>
                </div>
            <?php } ;?>
            

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
