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
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptDateDocument'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSaleQuantationRefDocInt'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSaleQuantationRefDocIntDate'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptCst'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSRCBch'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptVat'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSaleQuantationPrice'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptContact'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptOpenJobStaDoc'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <tr style="border-top: 1px solid #ddd !important;">
                                    <td class='xCNRptDetail text-center'><?=@$i+=1?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTXshDocNo']?></td>
                                    <td class='xCNRptDetail text-center'><?=date("d/m/Y", strtotime($aValue['FDXshDocDate']))?></td>
                                    <td class='xCNRptDetail text-center'><?=$aValue['FTXshRefInt']?></td>
                                    <td class='xCNRptDetail text-center'><?=date("d/m/Y", strtotime($aValue['FDXshRefIntDate']))?></td>
                                    <!-- สถานะเปิดเอกสาร -->
                                    <?php
                                        $tStaOdr = '';
                                        if ($aValue['FTXshStaQuo'] == 1) {
                                            $tStaOdr = language('report/report/report', 'tRptPhStaApv0');
                                        }elseif ($aValue['FTXshStaQuo'] == 2) {
                                            $tStaOdr = language('report/report/report', 'tRptPhStaApv1');
                                        }elseif ($aValue['FTXshStaQuo'] == 3) {
                                            $tStaOdr = language('report/report/report', 'tRptStaCrd3');
                                        }else{
                                            $tStaOdr = '';
                                        }
                                    ?>
                                    <!-- ------------- -->
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTCstName']?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTBchName']?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXshVat'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXshGrand'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-right'><?=$aValue['FTXshCtrName']?></td>
                                    <td class='xCNRptDetail text-center'><?=$tStaOdr?></td>
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
                                    <td class='xCNRptSumFooter text-center' colspan='7'><?php echo language('report/report/report', 'tRptTotal'); ?></td>
                                    <td class='xCNRptSumFooter text-right'><?php echo number_format($aDataTotal['FCXshVat_Footer'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptSumFooter text-right'><?php echo number_format($aDataTotal['FCXshGrand_Footer'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptSumFooter text-center' colspan='2'><?php //echo language('report/report/report', 'tRptTotal'); ?></td>
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
            <?php if( (isset($aDataFilter['tCstCodeFrom']) && !empty($aDataFilter['tCstCodeFrom'])) && (isset($aDataFilter['tCstCodeTo']) && !empty($aDataFilter['tCstCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ลูกค้า =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom'].' : </span>'.$aDataFilter['tCstCodeFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstTo'].' : </span>'.$aDataFilter['tCstCodeTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <!-- ===== ฟิวเตอร์ข้อมูล เครื่องจุดขาย=================================== -->
            <?php
                $tPos = '';
                if ($aDataFilter['tPosCodeSelect'] == 1) {
                    $tPos = language('report/report/report', 'tRptRetail');
                }elseif ($aDataFilter['tPosCodeSelect'] == 2) {
                    $tPos = language('report/report/report', 'tRptVending');
                }else{
                    $tPos = language('report/report/report', 'tRptAll');
                }
            ?>
            <?php if (isset($aDataFilter['tPosCodeSelect']) && !empty($aDataFilter['tPosCodeSelect'])) { ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptTaxSalePosType'); ?> : </span> <?php echo $tPos; ?></label>
                    </div>
                </div>
            <?php }else{ ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptTaxSalePosType'); ?> : </span> <?php echo $tPos; ?></label>
                    </div>
                </div>
            <?php } ?>

            <!-- ===== ฟิวเตอร์ข้อมูล สถานะเอกสาร=================================== -->
            <?php
                $tStaOdr = '';
                if ($aDataFilter['tStaApv'] == 1) {
                    $tStaOdr = language('report/report/report', 'tRptPhStaApv0');
                }elseif ($aDataFilter['tStaApv'] == 2) {
                    $tStaOdr = language('report/report/report', 'tRptPhStaApv1');
                }elseif ($aDataFilter['tStaApv'] == 3) {
                    $tStaOdr = language('report/report/report', 'tRptStaCrd3');
                }else{
                    $tStaOdr = language('report/report/report', 'tRptAll');
                }
            ?>
            <?php if (isset($aDataFilter['tStaApv']) && !empty($aDataFilter['tStaApv'])) { ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptOpenJobStaDoc'); ?> : </span> <?php echo $tStaOdr; ?></label>
                    </div>
                </div>
            <?php }else{ ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptOpenJobStaDoc'); ?> : </span> <?php echo $tStaOdr; ?></label>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>
