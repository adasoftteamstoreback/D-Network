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
                            <th nowrap class="text-center xCNRptColumnHeader" rowspan="2" style="vertical-align: middle; border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRowNumber'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" rowspan="2" style="vertical-align: middle; border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptSRCBch'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" colspan="2" style="border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptPreOrderBchTitle'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" colspan="5" style="border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptPreOrderTitle'); ?></th>
                        </tr>
                        <tr>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptXshDocNo'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptOpenJobStaDoc'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptDocPdtTwiSrcTypeAgn'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptXshDocNo'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptDateDocument'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptOpenJobStaDoc'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptPhStaApvdate'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <!-- สถานะเอกสาร -->
                                <?php
                                    $tStaApvPq = '';
                                    if ($aValue['FTXphStaApvPq'] == 1) {
                                        $tStaApvPq = language('report/report/report', 'tRptPhStaApv0');
                                    }elseif ($aValue['FTXphStaApvPq'] == 2) {
                                        $tStaApvPq = language('report/report/report', 'tRptPhStaApv1');
                                    }elseif ($aValue['FTXphStaApvPq'] == 3) {
                                        $tStaApvPq = language('report/report/report', 'tRptStaCrd3');
                                    }else{
                                        $tStaApvPq = '';
                                    }
                                ?>
                                <!-- ------------- //=date("d/m/Y", strtotime($aValue['FDXshRefIntDate']))-->
                                <tr style="border-top: 1px solid #ddd !important;">
                                    <td class='xCNRptDetail text-left'><?=@$i+=1?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTBchName']?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTXphRefInt']?></td>
                                    <td class='xCNRptDetail text-center'><?=$tStaApvPq?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTSplName']?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTXphDocNo']?></td>
                                    <td class='xCNRptDetail text-center'><?=date("d/m/Y", strtotime($aValue['FDXphDocDate']))?></td>
                                    <?php
                                        $tStaApv = '';
                                        if ($aValue['FTXphStaApv'] == 1) {
                                            $tStaApv = language('report/report/report', 'tRptPhStaApv0');
                                        }elseif ($aValue['FTXphStaApv'] == 2) {
                                            $tStaApv = language('report/report/report', 'tRptPhStaApv1');
                                        }elseif ($aValue['FTXphStaApv'] == 3) {
                                            $tStaApv = language('report/report/report', 'tRptStaCrd3');
                                        }else{
                                            $tStaApv = '';
                                        }
                                    ?>
                                    <td class='xCNRptDetail text-center'><?=$tStaApv?></td>
                                    <td class='xCNRptDetail text-center'><?=date("d/m/Y", strtotime($aValue['FDDateApv']))?></td>
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

            <?php if( (isset($aDataFilter['tPdtSupplierCodeFrom']) && !empty($aDataFilter['tPdtSupplierCodeFrom'])) && (isset($aDataFilter['tPdtSupplierCodeTo']) && !empty($aDataFilter['tPdtSupplierCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ผู้จำหน่าย =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplFrom'].' : </span>'.$aDataFilter['tPdtSupplierNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTo'].' : </span>'.$aDataFilter['tPdtSupplierNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tPdtSgpCodeFrom']) && !empty($aDataFilter['tPdtSgpCodeFrom'])) || (isset($aDataFilter['tPdtSgpCodeTo']) && !empty($aDataFilter['tPdtSgpCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล กลุ่มผู้จำหน่าย =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplGrpForm'].' : </span>'.$aDataFilter['tPdtSgpNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplGrpTo'].' : </span>'.$aDataFilter['tPdtSgpNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tPdtStyCodeFrom']) && !empty($aDataFilter['tPdtStyCodeFrom'])) || (isset($aDataFilter['tPdtStyCodeTo']) && !empty($aDataFilter['tPdtStyCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ประเภทผู้จำหน่าย =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTypeForm'].' : </span>'.$aDataFilter['tPdtStyNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTypeTo'].' : </span>'.$aDataFilter['tPdtStyNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

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
