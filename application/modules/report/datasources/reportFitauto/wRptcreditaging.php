<?php
$aDataReport = $aDataViewRpt['aDataReport'];
$aDataTextRef = $aDataViewRpt['aDataTextRef'];
$aDataFilter = $aDataViewRpt['aDataFilter'];
$nOptDecimalShow = FCNxHGetOptionDecimalShow();
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

    .table>thead:first-child>tr:nth-child(1)>td,
    .table>thead:first-child>tr:nth-child(1)>th {
        border-top: 1px solid black !important;
        border-bottom: 1px solid black !important;

        /* background-color: #CFE2F3 !important; */
    }

    .table>thead:first-child>tr:nth-child(2)>td,
    .table>thead:first-child>tr:nth-child(2)>th {
        border-bottom: 1px solid black !important;

        /* background-color: #CFE2F3 !important; */
    }

    .table>tbody>tr.xCNTrSubFooter {
        border-top: 1px solid black !important;
        border-bottom: 1px solid black !important;
        /* background-color: #CFE2F3 !important; */
    }

    .table>tbody>tr.xCNTrFooter {
        /* background-color: #CFE2F3 !important; */
        /* border-bottom : 6px double black !important; */
        /* border-top: dashed 1px #333 !important; */
        border-top: 1px solid black !important;
        border-bottom: 1px solid black !important;
    }

    .table tbody tr.xCNHeaderGroup,
    .table>tbody>tr.xCNHeaderGroup>td {
        /* color: #232C3D !important; */
        font-size: 18px !important;
        font-weight: 600;
    }

    .table>tbody>tr.xCNHeaderGroup>td:nth-child(4),
    .table>tbody>tr.xCNHeaderGroup>td:nth-child(5) {
        text-align: right;
    }

    /* .table>tbody>tr>td.xCNRptDetail:nth-child(8), .table>tbody>tr>td.xCNRptDetail:nth-child(8) {
        border-right: 1px dashed #ccc !important;
    } */
    /* .table>tbody>tr>td.xCNRptGrouPing:nth-child(7), .table>tbody>tr>td.xCNRptGrouPing:nth-child(7) {
        border-right: 1px dashed #ccc !important;
    }
    .table>tbody>tr.xCNTrFooter>td:nth-child(8), .table>tbody>tr.xCNTrFooter>td:nth-child(8) {
        border-right: 1px dashed #ccc !important;
    } */
    /*แนวนอน*/
    @media print {
        @page {
            size: A4 landscape;
            /* margin: 5mm 5mm 5mm 5mm; */
            /* margin: 1.5mm 1.5mm 1.5mm 1.5mm; */
        }
    }

    /*แนวตั้ง*/
    /*@media print{@page {size: portrait}}*/
</style>
<div id="odvRptAdjPriceHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <div class="row">
                <?php include 'application\modules\report\datasources\Address\wRptAddress.php'; ?>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 report-filter">

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center">
                                <label class="xCNRptTitle"><?php echo $aDataTextRef['tTitleReport']; ?></label>
                            </div>
                        </div>
                    </div>

                    <?php if ((isset($aDataFilter['tRptDocDateFrom']) && !empty($aDataFilter['tRptDocDateFrom'])) && (isset($aDataFilter['tRptDocDateTo']) && !empty($aDataFilter['tRptDocDateTo']))) : ?>
                        <!-- ===== ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ================= ========= -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateFrom'] ?></label> <label><?= date('d/m/Y', strtotime($aDataFilter['tRptDocDateFrom'])); ?> </label>&nbsp;
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateTo'] ?></label> <label><?= date('d/m/Y', strtotime($aDataFilter['tRptDocDateTo'])); ?> </label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ((isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                        <!-- ===== ฟิวเตอร์ข้อมูล สาขา =================================== -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptTaxSalePosFilterBchFrom'] ?></label> <label><?= $aDataFilter['tBchNameFrom']; ?></label>&nbsp;
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptTaxSalePosFilterBchTo'] ?></label> <label><?= $aDataFilter['tBchNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php }; ?>

                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:30%;border-bottom:1px dashed #333 !important;" colspan="5"><?php echo language('report/report/report', 'tRptCreditCol1'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:30%;border-bottom:1px dashed #333 !important;border-left:1px dashed #333 !important;" colspan="3"><?php echo language('report/report/report', 'tRptCreditCol2'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:30%;border-bottom:1px dashed #333 !important;border-left:1px dashed #333 !important;" colspan="7"><?php echo language('report/report/report', 'tRptCreditCol3'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;border-left:1px dashed #333 !important;vertical-align: middle;" rowspan="2"><?php echo language('report/report/report', 'tRptCreditCol4'); ?></th>
                        </tr>
                        <tr>
                            <th nowrap class="text-left xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol5'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol6'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol7'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol8'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol9'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol10'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol11'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol12'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol13'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol14'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol15'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol16'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol17'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol18'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px dashed #333 !important; width:5%;"><?php echo language('report/report/report', 'tRptCreditCol19'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <tr>
                                    <?php if($aValue['rtPartitionSPL'] == 1){ ?>
                                        <tr >
                                            <td class="text-left xCNRptGrouPing" colspan="3">(<?=$aValue['FTSplCode']?>) <?=$aValue['FTSplName']?></td>
                                            <td class="text-right xCNRptGrouPing" colspan="2">วงเงิน <?=number_format($aValue['FCSplCrLimit'],2)?></td>
                                        </tr>
                                    <?php } ?>

                                    <td class="text-left xCNRptDetail"><?=date("d/m/Y", strtotime($aValue['FDXphDueDate']))?></td>
                                    <td class="text-left xCNRptDetail"><?=$aValue['FTXphDocNo']?></td>
                                    <td class="text-left xCNRptDetail"><?=($aValue['FTXphRefInt'] == '') ? '-' : $aValue['FTXphRefInt']?></td>
                                    <td class="text-left xCNRptDetail"><?=date("d/m/Y", strtotime($aValue['FDXphDocDate']))?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue["FNXphCrTerm"], $nOptDecimalShow)?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphBFDue60'], $nOptDecimalShow)?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphBFDue31And60'], $nOptDecimalShow)?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphBFDue0And30'], $nOptDecimalShow)?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphOVDue1'], $nOptDecimalShow)?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphOVDue2And7'], $nOptDecimalShow)?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphOVDue8And15'], $nOptDecimalShow)?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphOVDue16And30'], $nOptDecimalShow)?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphOVDue31And60'], $nOptDecimalShow)?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphOVDue61And90'], $nOptDecimalShow)?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphOVDue90'], $nOptDecimalShow)?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXshLeft'], $nOptDecimalShow)?></td>

                                    <?php if($aValue['rtPartitionSPL'] == $aValue['rtPartitionCountSPL']){ ?>
                                        <tr style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='xCNTrFooter'>
                                            <td class="text-left xCNRptGrouPing" colspan="5">รวม (<?=$aValue['FTSplCode']?>) <?=$aValue['FTSplName']?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphBFDue60_SPL_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphBFDue31And60_SPL_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphBFDue0And30_SPL_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue1_SPL_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue2And7_SPL_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue8And15_SPL_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue16And30_SPL_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue31And60_SPL_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue61And90_SPL_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue90_SPL_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXshLeft_SPL_Footer'],$nOptDecimalShow ) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            <?php
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                if ($nPageNo == $nTotalPage) { ?>
                                    <tr style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='xCNTrFooter'>
                                        <td class="text-left xCNRptGrouPing" colspan="5">รวม</td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphBFDue60_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphBFDue31And60_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphBFDue0And30_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue1_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue2And7_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue8And15_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue16And30_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue31And60_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue61And90_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphOVDue90_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXshLeft_Footer'],$nOptDecimalShow ) ?></td>
                                    </tr>
                            <?php } ?>
                        <?php }else { ?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?=$aDataTextRef['tRptAdjStkNoData']; ?></td></tr>
                        <?php } ;?>
                    </tbody>
                </table>
            </div>

            <div class="xCNRptFilterTitle">
                <div class="text-left">
                    <label class="xCNTextConsOth"><?= $aDataTextRef['tRptConditionInReport']; ?></label>
                </div>
            </div>

            <?php if (isset($aDataFilter['tBchCodeSelect']) && !empty($aDataFilter['tBchCodeSelect'])) : ?>
                <!-- ===== ฟิวเตอร์ข้อมูล สาขา =========================================== -->
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['bBchStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tBchNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ((isset($aDataFilter['tPdtSupplierCodeFrom']) && !empty($aDataFilter['tPdtSupplierCodeFrom'])) && (isset($aDataFilter['tPdtSupplierCodeTo']) && !empty($aDataFilter['tPdtSupplierCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ผู้จำหน่าย =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplFrom'] . ' : </span>' . $aDataFilter['tPdtSupplierNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTo'] . ' : </span>' . $aDataFilter['tPdtSupplierNameTo']; ?></label>
                    </div>
                </div>
            <?php }; ?>

            <?php if ((isset($aDataFilter['tPdtSgpCodeFrom']) && !empty($aDataFilter['tPdtSgpCodeFrom'])) || (isset($aDataFilter['tPdtSgpCodeTo']) && !empty($aDataFilter['tPdtSgpCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล กลุ่มผู้จำหน่าย =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplGrpForm'] . ' : </span>' . $aDataFilter['tPdtSgpNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplGrpTo'] . ' : </span>' . $aDataFilter['tPdtSgpNameTo']; ?></label>
                    </div>
                </div>
            <?php }; ?>

            <?php if ((isset($aDataFilter['tPdtStyCodeFrom']) && !empty($aDataFilter['tPdtStyCodeFrom'])) || (isset($aDataFilter['tPdtStyCodeTo']) && !empty($aDataFilter['tPdtStyCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ประเภทผู้จำหน่าย =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTypeForm'] . ' : </span>' . $aDataFilter['tPdtStyNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTypeTo'] . ' : </span>' . $aDataFilter['tPdtStyNameTo']; ?></label>
                    </div>
                </div>
            <?php }; ?>

            <!-- ===== ฟิวเตอร์ข้อมูล สถานะเอกสาร=================================== -->
            <?php
            $tStaOdr = '';
            if ($aDataFilter['tStaApv'] == 1) {
                $tStaOdr = language('report/report/report', 'tRptPhStaApv0');
            } elseif ($aDataFilter['tStaApv'] == 2) {
                $tStaOdr = language('report/report/report', 'tRptPhStaApv1');
            } elseif ($aDataFilter['tStaApv'] == 3) {
                $tStaOdr = language('report/report/report', 'tRptStaCrd3');
            } else {
                $tStaOdr = language('report/report/report', 'tRptAll');
            }
            ?>
            <?php if (isset($aDataFilter['tStaApv']) && !empty($aDataFilter['tStaApv'])) { ?>
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