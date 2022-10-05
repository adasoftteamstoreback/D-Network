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
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPoBch'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptXshDocNo'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptDateDocument'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPoRefABB'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptRefDate'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPoToCst'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', ''); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', ''); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', ''); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSalePendingTel'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', ''); ?></th>
                        </tr>
                        <tr>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', ''); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPdtCode'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPdtName'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', ''); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptBarCode'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptUnit'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPricePerUnit'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptQty2'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSales'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptDiscount'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptTotal'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <?php if ($aValue['FNTxnType'] == 1) { ?>
                                    <tr style="border-top: 1px solid #ddd !important;">
                                        <td class='xCNRptDetail text-left'><b><?=$aValue['FTBchName']?></b></td>
                                        <td class='xCNRptDetail text-left'><b><?=$aValue['FTXshDocNo']?></b></td>
                                        <td class='xCNRptDetail text-center'><b><?=date('Y-m-d', strtotime($aValue['FDXshDocDate']))?></b></td>
                                        <td class='xCNRptDetail text-left'><b><?=$aValue['FTXshRefDocNo']?></b></td>
                                        <td class='xCNRptDetail text-center'><b><?=date('Y-m-d', strtotime($aValue['FDXshRefDocDate']))?></b></td>
                                        <td class='xCNRptDetail text-left' colspan="4"><b><?=$aValue['FTCstName']?></b></td>
                                        <td class='xCNRptDetail text-left' colspan="2"><b><?=$aValue['FTXshCstTel']?></b></td>
                                    </tr>
                                <?php }else{ ?>
                                    <tr>
                                        <td class='xCNRptDetail text-left'></td>
                                        <td class='xCNRptDetail text-left'><?=$aValue['FTPdtCode']?></td>
                                        <td class='xCNRptDetail text-left' colspan="2"><?=$aValue['FTPdtName']?></td>
                                        <td class='xCNRptDetail text-left'><?=$aValue['FTXsdBarCode']?></td>
                                        <td class='xCNRptDetail text-right'><?=$aValue['FTPunName']?></td>
                                        <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdSetPrice'], $nOptDecimalShow)?></td>
                                        <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdQty'], $nOptDecimalShow)?></td>
                                        <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXshTotal'], $nOptDecimalShow)?></td>
                                        <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXshDisChg'], $nOptDecimalShow)?></td>
                                        <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXshGrand'], $nOptDecimalShow)?></td>
                                    </tr>
                                <?php } ?>

                                <?php if ($aValue['rtPartitionDOC'] == $aValue['rtPartitionCountDOC']) { ?>
                                    <tr style="border-top: 1px solid #ddd !important; border-bottom: 1px solid #ddd !important;">
                                        <td class='xCNRptDetail text-left' colspan="7"><b><?php echo language('report/report/report', 'tRptTotal'); ?> : <?=$aValue['FTXshDocNo']?></b></td>
                                        <td class='xCNRptDetail text-right'><?=number_format($aValue['rtSumQty'], $nOptDecimalShow)?></td>
                                        <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXshTotalHD'], $nOptDecimalShow)?></td>
                                        <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXshDisChgHD'], $nOptDecimalShow)?></td>
                                        <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXshGrandHD'], $nOptDecimalShow)?></td>
                                    </tr>
                                <?php } ?>
                                
                            <?php endforeach; ?>
                            <?php

                            //Summary Footer
                            $aDataTotal = $aDataReport['aRptData'][0];
                            $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];

                            if ($nPageNo == $nTotalPage) { ?>
                                <!-- <tr class='xCNTrFooter'>
                                    <td class='xCNRptSumFooter text-center' colspan='5'><?php echo language('report/report/report', 'tRptTotal'); ?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdCostEX_Footer'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdNetSale_Footer'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdDiff_Footer'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXsdPerDiff_Footer'], $nOptDecimalShow)?></td>
                                <tr> -->
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

            <!-- ===== ฟิวเตอร์ข้อมูล สถานะเอกสาร=================================== -->
            <?php
                $tStaOdr = '';
                if ($aDataFilter['tPdtRptStaApv'] == 1) {
                    $tStaOdr = language('report/report/report', 'tRptPhStaApv0');
                } elseif ($aDataFilter['tPdtRptStaApv'] == 2) {
                    $tStaOdr = language('report/report/report', 'tRptPhStaApv1');
                } elseif ($aDataFilter['tPdtRptStaApv'] == 3) {
                    $tStaOdr = language('report/report/report', 'tRptStaCrd3');
                } else {
                    $tStaOdr = language('report/report/report', 'tRptAll');
                }
            ?>
            <?php if (isset($aDataFilter['tPdtRptStaApv']) && !empty($aDataFilter['tPdtRptStaApv'])) { ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptOpenJobStaDoc'); ?> : </span> <?php echo $tStaOdr; ?></label>
                    </div>
                </div>
            <?php } else { ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptOpenJobStaDoc'); ?> : </span> <?php echo $tStaOdr; ?></label>
                    </div>
                </div>
            <?php } ?>
            
            <!-- ===== ฟิวเตอร์ข้อมูล ลูกค้า ========================== -->
            <?php if((isset($aDataFilter['tCstCodeTo']) && !empty($aDataFilter['tCstCodeTo'])) && (isset($aDataFilter['tCstCodeFrom']) && !empty($aDataFilter['tCstCodeFrom']))): ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom'] . ' : </span>' . $aDataFilter['tCstNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstTo'] . ' : </span>' . $aDataFilter['tCstNameTo']; ?></label>
                    </div>
                </div>
            <?php endif;?>

            <!-- ===== ฟิวเตอร์ข้อมูล กลุ่มลูกค้า ========================== -->
            <?php if((isset($aDataFilter['tCstGrpCodeFrom']) && !empty($aDataFilter['tCstGrpCodeFrom'])) && (isset($aDataFilter['tCstGrpCodeTo']) && !empty($aDataFilter['tCstGrpCodeTo']))): ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptCstGrpForm') . ' : </span>' . $aDataFilter['tCstGrpNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptCstGrpTo') . ' : </span>' . $aDataFilter['tCstGrpNameTo']; ?></label>
                    </div>
                </div>
            <?php endif;?>

            <!-- ===== ฟิวเตอร์ข้อมูล ประเภทลูกค้า ========================== -->
            <?php if((isset($aDataFilter['tCstTypeCodeFrom']) && !empty($aDataFilter['tCstTypeCodeFrom'])) && (isset($aDataFilter['tCstTypeCodeTo']) && !empty($aDataFilter['tCstTypeCodeTo']))): ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptCstTypeForm') . ' : </span>' . $aDataFilter['tCstTypeNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptCstTypeTo') . ' : </span>' . $aDataFilter['tCstTypeNameTo']; ?></label>
                    </div>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>
