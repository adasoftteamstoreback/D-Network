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
<div id="odvRptPobyBchbyPdteHtml">
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
                            <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) : ?>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="text-center xCNRptFilter">
                                            <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateFrom']; ?> </span> <?php echo date("d/m/Y", strtotime($aDataFilter['tDocDateFrom'])); ?></label>
                                            <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateTo']; ?> </span> <?php echo date("d/m/Y", strtotime($aDataFilter['tDocDateTo'])); ?></label>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
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
                            <th nowrap class="text-left   xCNRptColumnHeader" style="width:10%;">ผู้จำหน่าย/เจ้าหนี้</th>
                            <th nowrap class="text-left   xCNRptColumnHeader" style="width:10%;">เลขที่เอกสาร</th>
                            <th nowrap class="text-left   xCNRptColumnHeader" style="width:10%;">วันที่เอกสาร</th>
                            <th nowrap class="text-left   xCNRptColumnHeader" style="width:10%;">เลขที่เอกสารอ้างอิง</th>
                            <th nowrap class="text-right   xCNRptColumnHeader" style="width:10%;">ยอดยกเว้นภาษี</th>
                            <th nowrap class="text-right   xCNRptColumnHeader" style="width:10%;">ยอดแยกภาษี</th>
                            <th nowrap class="text-right   xCNRptColumnHeader" style="width:10%;">ภาษี</th>
                            <th nowrap class="text-right   xCNRptColumnHeader" style="width:10%;">ยอดรวม</th>
                            <th nowrap class="text-center   xCNRptColumnHeader" style="width:10%;">พนักงานเพิ่มหนี้</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php
                            // Set ตัวแปร SumSub Footer
                            $nQty_SubTotal  = 0;
                            // Set ตัวแปร Sum Footer
                            $nQty_Footer    = 0;
                            // Check Branch 
                            $tChkBranch     = "";
                            $tChkProduct    = "";
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>


                                <?php if ($aValue['FNFmtAllRow'] == 1) { ?>
                                    <tr>
                                        <td nowrap class="xCNRptDetail" colspan="12"><strong>เจ้าหนี้ : <?php echo $aValue["FTSplName"] . ' / ' . $aValue["FTSplCode"]; ?></strong></td>
                                    </tr>
                                <?php } ?>

                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                                    <!-- <td nowrap class="text-center   xCNRptDetail"><?php echo $aValue["FDXphDocDate"]; ?></td>
                                date_format($date,"Y/m/d H:i:s"); -->
                                    <td nowrap class="text-left   xCNRptDetail"></td>
                                    <td nowrap class="text-left   xCNRptDetail"><?php echo $aValue["FTXphDocNo"]; ?></td>
                                    <td nowrap class="text-left   xCNRptDetail"><?php echo  date("d/m/Y", strtotime($aValue["FDXphDocDate"])); ?></td>
                                    <td nowrap class="text-left   xCNRptDetail"><?php echo $aValue["FTXshRefDocNo"]; ?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?php echo number_format($aValue["FCXphPaid"], $nOptDecimalShow); ?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?php echo number_format($aValue["FCXphRemain"], $nOptDecimalShow); ?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?php echo number_format($aValue["FCXphVat"], $nOptDecimalShow); ?></td>
                                    <td nowrap class="text-right   xCNRptDetail"><?php echo number_format($aValue["FCXphGrand"], $nOptDecimalShow); ?></td>
                                    <td nowrap class="text-center   xCNRptDetail"><?php echo $aValue["FTUsrName"]; ?></td>

                                </tr>

                                <?php if ($aValue['FNFmtAllRow'] == $aValue['FNFmtEndRow']) { ?>
                                    <tr class="text-left xCNRptDetail" style=" border-left: 12px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                                    <tr>
                                        <td nowrap class="xCNRptDetail" colspan="4"><strong>รวม : <?php echo $aValue["FTSplName"] . ' / ' . $aValue["FTSplCode"] . ' ' . $aValue['FNFmtEndRow'] . ' ' . 'รายการ'; ?></strong></td>
                                        <td nowrap class="text-right   xCNRptDetail"><?php echo number_format($aValue["FCXphPaid_SubTotal"], $nOptDecimalShow); ?></td>
                                        <td nowrap class="text-right   xCNRptDetail"><?php echo number_format($aValue["FCXphRemain_SubTotal"], $nOptDecimalShow); ?></td>
                                        <td nowrap class="text-right   xCNRptDetail"><?php echo number_format($aValue["FCXphVat_SubTotal"], $nOptDecimalShow); ?></td>
                                        <td nowrap class="text-right   xCNRptDetail"><?php echo number_format($aValue["FCXphGrand_SubTotal"], $nOptDecimalShow); ?></td>


                                    </tr>
                                    <tr class="text-left xCNRptDetail" style=" border-left: 12px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                                <?php } ?>


                            <?php endforeach; ?>
                            <?php $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                            if ($nPageNo == $nTotalPage) { ?>
                                <tr>
                                    <td nowrap class="xCNRptDetail"><strong>รวม<strong></td>


                                    <td nowrap class="xCNRptDetail" colspan="3"></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXphPaid_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXphRemain_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXphVat_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXphGrand_Footer'], $nOptDecimalShow) ?></strong></td>

                                </tr>
                                <tr class="text-left xCNRptDetail" style=" border-left: 11px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                            <?php } ?>
                        <?php else : ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?php echo @$aDataTextRef['tRptAdjStkNoData']; ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="xCNRptFilterTitle">
                <div class="text-left">
                    <label class="xCNTextConsOth"><?= $aDataTextRef['tRptConditionInReport']; ?></label>
                </div>
            </div>
            <?php if (isset($aDataFilter['tBchCodeSelect']) && !empty($aDataFilter['tBchCodeSelect'])) : ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
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

            <!-- ============================ ฟิวเตอร์ข้อมูล แคชเชียร์ ============================ -->
            <?php if ((isset($aDataFilter['tCashierCodeFrom']) && !empty($aDataFilter['tCashierCodeFrom'])) && (isset($aDataFilter['tCashierCodeTo']) && !empty($aDataFilter['tCashierCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCashierFrom'] . ' : </span>' . $aDataFilter['tCashierNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCashierTo'] . ' : </span>' . $aDataFilter['tCashierNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($aDataFilter['tCashierCodeSelect']) && !empty($aDataFilter['tCashierCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCashierFrom']; ?> : </span> <?php echo ($aDataFilter['bCashierStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tCashierCodeSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>