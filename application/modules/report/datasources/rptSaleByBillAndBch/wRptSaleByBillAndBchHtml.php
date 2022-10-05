<?php
    $aDataReport        = $aDataViewRpt['aDataReport'];
    $aDataTextRef       = $aDataViewRpt['aDataTextRef'];
    $aDataFilter        = $aDataViewRpt['aDataFilter'];
    $mRptSaleByBillAndBch    = $aDataViewRpt['mRptSaleByBillAndBch'];
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
        border-bottom: 0px transparent !important;
        /*border-bottom : 1px solid black !important;
        background-color: #CFE2F3 !important;*/
    }

    .table>thead:first-child>tr:first-child>td:nth-child(1),
    .table>thead:first-child>tr:first-child>th:nth-child(1),
    .table>thead:first-child>tr:first-child>td:nth-child(2),
    .table>thead:first-child>tr:first-child>th:nth-child(2),
    .table>thead:first-child>tr:first-child>td:nth-child(3),
    .table>thead:first-child>tr:first-child>th:nth-child(3) {
        border-bottom: 1px dashed #ccc !important;
    }

    .table>thead:first-child>tr:last-child>td,
    .table>thead:first-child>tr:last-child>th {
        /*border-top: 1px solid black !important;*/
        border-bottom: 1px solid black !important;
        /*background-color: #CFE2F3 !important;*/
    }

    .table>tbody>tr.xCNTrSubFooter {
        border-top: 1px solid black !important;
        border-bottom: 1px solid black !important;
        /*background-color: #CFE2F3 !important;*/
    }

    .table>tbody>tr.xCNTrFooter {
        border-top: 1px solid black !important;
        /*background-color: #CFE2F3 !important;*/
        border-bottom: 6px double black !important;
    }

    .table>tbody>tr.xCNRptLastPdtList>td:nth-child(3),
    .table>tbody>tr.xCNRptLastPdtList>td:nth-child(4),
    .table>tbody>tr.xCNRptLastPdtList>td:nth-child(5),
    .table>tbody>tr.xCNRptLastPdtList>td:nth-child(6),
    .table>tbody>tr.xCNRptLastPdtList>td:nth-child(7) {
        border: 0px solid black !important;
        border-bottom: 1px dashed #ccc !important;
    }

    .table tbody tr.xCNRptLastGroupTr,
    .table>tbody>tr.xCNRptLastGroupTr>td {
        border: 0px solid black !important;
        border-bottom: 1px dashed #ccc !important;
    }

    .table tbody tr.xCNRptSumFooterTrTop,
    .table>tbody>tr.xCNRptSumFooterTrTop>td {
        border: 0px solid black !important;
        border-top: 1px solid black !important;
    }

    .table tbody tr.xCNRptSumFooterTrBottom,
    .table>tbody>tr.xCNRptSumFooterTrBottom>td {
        border: 0px solid black !important;
        border-bottom: 1px solid black !important;
    }

    .table>tfoot>tr {
        border-top: 1px solid black !important;
        /*background-color: #CFE2F3 !important;*/
        border-bottom: 6px double black !important;
    }

    /*แนวนอน*/
    /* @media print{@page {size: landscape}} */
    /*แนวตั้ง*/
    @media print {
        @page {
            size: portrait
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
                                <div class="text-center xCNRptFilter">
                                    <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateFrom']; ?> : </span> <?php echo date('d/m/Y',strtotime($aDataFilter['tDocDateFrom'])); ?></label>
                                    <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateTo']; ?> : </span> <?php echo date('d/m/Y',strtotime($aDataFilter['tDocDateTo'])); ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ((isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo $aDataFilter['tBchNameFrom']; ?></label>
                                    <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchTo']; ?> : </span> <?php echo $aDataFilter['tBchNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php }; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <label class="xCNRptDataPrint"><?php echo $aDataTextRef['tRptDatePrint'] . ' ' . date('d/m/Y') . ' ' . $aDataTextRef['tRptTimePrint'] . ' ' . date('H:i:s'); ?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-left xCNRptColumnHeader" width="10%" ><?php echo $aDataTextRef['tRptSalByBranchBchCode']; ?></th>
                            <th class="text-left xCNRptColumnHeader" width="10%"><?php echo $aDataTextRef['tRptSalByBranchBchName']; ?></th>
                            <th class="text-left xCNRptColumnHeader" width="20%"><?php echo $aDataTextRef['tRptBillNo']; ?></th>
                            <th class="text-left xCNRptColumnHeader" width="17%"><?php echo $aDataTextRef['tRptTaxSalePosDocRef']; ?></th>
                            <th class="text-left xCNRptColumnHeader" width="15%"><?php echo $aDataTextRef['tRptCst']; ?></th>
                            <th class="text-left xCNRptColumnHeader" colspan="3"><?php echo $aDataTextRef['tRptDate']; ?></th>
                        </tr>
                        <tr>
                            <th class="text-left xCNRptColumnHeader" ><?php echo $aDataTextRef['tSeqPdtCode']; ?></th>
                            <th class="text-left xCNRptColumnHeader" colspan="2"><?php echo $aDataTextRef['tRptPdtName']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptQty']; ?></th>
                            <th class="text-right xCNRptColumnHeader"><?php echo $aDataTextRef['tRptPricePerUnit']; ?></th>
                            <th class="text-right xCNRptColumnHeader"><?php echo $aDataTextRef['tRptSales']; ?></th>
                            <th class="text-right xCNRptColumnHeader"><?php echo $aDataTextRef['tRptDiscount']; ?></th>
                            <th class="text-right xCNRptColumnHeader"><?php echo $aDataTextRef['tRptGrandSale']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php
                                $bIsRcFirst     = true;
                                $tLastPdtList   = '';
                                $nIndex         = 1;
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <?php
                                    $tRptDocNo          = $aValue["FTXshDocNo"];
                                    $tRptDocDate        = date('Y-m-d H:i:s', strtotime($aValue["FDXshDocDate"]));
                                    $tRptCstCode        = $aValue["FTCstCode"];
                                    $tRptCstName        = $aValue["FTCstName"];
                                    $tRptRefInt         = $aValue['FTXshRefInt'];
                                    $tRptPdtCode        = $aValue['FTPdtCode'];
                                    $tRptPdtName        = $aValue['FTPdtName'];
                                    $tRptRcvName        = $aValue['FTRcvName'];
                                    $tRptXrcRefNo1      = $aValue['FTXrcRefNo1'];
                                    $tRptBnkName        = $aValue['FTBnkName'];
                                    $tRptPunName        = $aValue['FTPunName'];
                                    $nRptXsdQty         = $aValue['FCXsdQty'];
                                    $nRptXsdSetPrice    = $aValue['FCXsdSetPrice'];
                                    $nRptXsdAmt         = $aValue['FCXsdAmt'];
                                    $nRptXsdDis         = $aValue['FCXsdDis'];
                                    $nRptXsdNet         = $aValue['FCXsdNet'];
                                    $nRptXrcNet         = empty($aValue['FCXrcNet']) ? 0 : $aValue['FCXrcNet'];
                                    $nRptSumXsdAmt      = $aValue['FCXsdAmt_SubTotal'];
                                    $nRptSumXsdDis      = $aValue['FCXsdDis_SubTotal'];
                                    $nRptSumXsdNet      = $aValue['FCXsdNet_SubTotal'];
                                    $nGroupMember       = $aValue["FNRptGroupMember"];
                                    $nRowPartID         = $aValue["FNRowPartID"];
                                    $nRowType           = $aValue['FNType'];
                                    $nCount_DT          = $aValue['FNCount_DT'];
                                    $nCount_RC          = $aValue['FNCount_RC'];
                                    $tBchCode           = $aValue['FTBchCode'];
                                    $tBchName           = $aValue['FTBchName'];
                                    $nLastDT            = $nCount_DT + 1;
                                    $nFirstRC           = $nLastDT + 1;
                                    $nLastRC            = $nLastDT + $nCount_RC;
                                ?>

                                <?php if ($nRowPartID == 1) { ?>
                                    <?php $nIndex = 1; ?>
                                    <tr>
                                        <td class="xCNRptGrouPing"><?=$tBchCode; ?></td>
                                        <td class="xCNRptGrouPing"><?=$tBchName; ?></td>
                                        <td class="xCNRptGrouPing"><?=$tRptDocNo; ?></td>
                                        <td class="xCNRptGrouPing"><?=$tRptRefInt; ?></td>
                                        <td class="xCNRptGrouPing"><?=empty($tRptCstCode) ? $aDataTextRef['tRptCstNormal'] : '(' . $tRptCstCode . ')' . $tRptCstName; ?></td>
                                        <td class="xCNRptGrouPing" colspan="2"><?=$tRptDocDate; ?></td>
                                    </tr>
                                <?php } ?>

                                <?php if ($nRowPartID > 1 && $nRowType == 2) { // Display Body Data ?>
                                    <tr class="<?=$tLastPdtList; ?>">
                                        <td class="xCNRptDetail"><?=$nIndex . ' '; ?><?=$tRptPdtCode; ?></td>
                                        <td class="xCNRptDetail" colspan="2"><?=$tRptPdtName; ?></td>
                                        <td class="xCNRptDetail"><?=number_format($nRptXsdQty,$nOptDecimalShow); ?> <?=$tRptPunName; ?></td>
                                        <td class="text-right xCNRptDetail"><?=number_format($nRptXsdSetPrice, $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptDetail"><?=number_format($nRptXsdAmt, $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptDetail"><?=number_format($nRptXsdDis, $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptDetail"><?=number_format($nRptXsdNet, $nOptDecimalShow); ?> V</td>
                                    </tr>
                                    <?php $nIndex++; ?>
                                <?php } ?>

                                <?php if (($nRowType == 3 && $nFirstRC == $nRowPartID) || ($nRowType == 3 && $nCount_RC > 1)) { // Display Sub Sum RC ?>
                                    <?php $bIsRcFirst = false; ?>
                                    <tr>
                                        <td class="xCNRptGrouPing" colspan="5"><?=$tRptRcvName; ?> <?=' ' . $tRptXrcRefNo1 . $tRptBnkName . ' : ' . number_format($nRptXrcNet, $nOptDecimalShow); ?> บาท</td>

                                        <?php if(($nFirstRC == $nRowPartID)){?>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($nRptSumXsdAmt, $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($nRptSumXsdDis, $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($nRptSumXsdNet, $nOptDecimalShow); ?></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>

                                <?php if ($nRowPartID == $nGroupMember && $nRowType == 3 && !$bIsRcFirst) { // Display Sub Sum HD ?>
                                    <?php
                                        $bIsRcFirst     = true;
                                        $aGetHDParams   = [
                                            'tDocNo'            => $tRptDocNo
                                        ];
                                        $aHD = $mRptSaleByBillAndBch->FMaMRPTGetHDByDocNo($aGetHDParams);
                                    ?>
                                    <tr>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td class="xCNRptGrouPing" colspan="2"><?=$aDataTextRef['tRptPdtHaveTaxPerTax']; ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=$aDataTextRef['tRptDiscount']; ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aHD['FCXshDis'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($nRptSumXsdNet + $aHD['FCXshDis'], $nOptDecimalShow); ?></td>
                                    </tr>
                                    <tr class="xCNRptLastGroupTr">
                                        <td></td>
                                        <td class="xCNRptGrouPing" colspan="2"></td>
                                        <td class="xCNRptGrouPing" colspan="2"><?=number_format($aHD['FCXshVatable'], $nOptDecimalShow); ?> / <?=number_format($aHD['FCXshVat'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=$aDataTextRef['tRptRndVal']; ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aHD['FCXshRnd'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aHD['FCXshGrand'], $nOptDecimalShow); ?></td>
                                    </tr>
                                <?php } ?>

                                <!--รวมยอดตามสาขา-->
                                <?php if($aValue['PARTTITIONBYBCH'] == $aValue['PARTTITIONBYBCH_COUNT']){ ?>
                                    <?php
                                        $aGetHDParams   = [
                                            'tBCHCode'  => $tBchCode
                                        ];
                                        $aSumFooter     = $mRptSaleByBillAndBch->FMaMRPTSumFooterAllByBCH($aGetHDParams);
                                    ?>
                                    <tr class="xCNRptSumFooterTrTop">
                                        <td class="xCNRptSumFooter" colspan="5">รวม <?=$tBchName; ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXsdAmt_SumFooter'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXsdDis_SumFooter'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXsdNet_SumFooter'], $nOptDecimalShow); ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td class="xCNRptSumFooter" colspan="2"><?=$aDataTextRef['tRptPdtHaveTaxPerTax']; ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=$aDataTextRef['tRptDiscount']; ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXshDis_SumFooter'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXsdNet_SumFooter'] + $aSumFooter['FCXshDis_SumFooter'], 2); ?></td>
                                    </tr>
                                    <tr class="xCNRptSumFooterTrBottom">
                                        <td></td>
                                        <td class="xCNRptSumFooter" colspan="2"></td>
                                        <td class="xCNRptSumFooter" colspan="2"><?=number_format($aSumFooter['FCXshVatable_SumFooter'], $nOptDecimalShow); ?> / <?=number_format($aSumFooter['FCXshVat_SumFooter'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=$aDataTextRef['tRptRndVal']; ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXshRnd_SumFooter'], $nOptDecimalShow); ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXshGrand_SumFooter'], $nOptDecimalShow); ?></td>
                                    </tr>
                                <?php } ?>

                            <?php } ?>

                            <?php 
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                if ($nPageNo == $nTotalPage) { ?>
                                    <?php
                                        $aGetHDParams = [
                                            'tDocNo'        => $tRptDocNo
                                        ];
                                        $aSumFooter = $mRptSaleByBillAndBch->FMaMRPTSumFooterAll($aGetHDParams);
                                    ?>
                                <tr class="xCNRptSumFooterTrTop">
                                    <td class="xCNRptSumFooter" colspan="5"><?=$aDataTextRef['tRptTotalAllSale']; ?></td>
                                    <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXsdAmt_SumFooter'], $nOptDecimalShow); ?></td>
                                    <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXsdDis_SumFooter'], $nOptDecimalShow); ?></td>
                                    <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXsdNet_SumFooter'], $nOptDecimalShow); ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2"></td>
                                    <td class="xCNRptSumFooter" colspan="2"><?=$aDataTextRef['tRptPdtHaveTaxPerTax']; ?></td>
                                    <td class="text-right xCNRptSumFooter"><?=$aDataTextRef['tRptDiscount']; ?></td>
                                    <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXshDis_SumFooter'], $nOptDecimalShow); ?></td>
                                    <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXsdNet_SumFooter'] + $aSumFooter['FCXshDis_SumFooter'], 2); ?></td>
                                </tr>
                                <tr class="xCNRptSumFooterTrBottom">
                                    <td></td>
                                    <td class="xCNRptSumFooter" colspan="2"></td>
                                    <td class="xCNRptSumFooter" colspan="2"><?=number_format($aSumFooter['FCXshVatable_SumFooter'], $nOptDecimalShow); ?> / <?=number_format($aSumFooter['FCXshVat_SumFooter'], $nOptDecimalShow); ?></td>
                                    <td class="text-right xCNRptSumFooter"><?=$aDataTextRef['tRptRndVal']; ?></td>
                                    <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXshRnd_SumFooter'], $nOptDecimalShow); ?></td>
                                    <td class="text-right xCNRptSumFooter"><?=number_format($aSumFooter['FCXshGrand_SumFooter'], $nOptDecimalShow); ?></td>
                                </tr>
                            <?php } ?>
                        <?php }else{ ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?=language('common/main/main', 'tCMNNotFoundData') ?></td>
                            </tr>
                        <?php } ?>
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

            <!-- ============================ ฟิวเตอร์ข้อมูล จุดขาย ============================ -->
            <?php if ((isset($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeFrom'])) && (isset($aDataFilter['tPosCodeTo']) && !empty($aDataFilter['tPosCodeTo']))) : ?>
            <div class="xCNRptFilterBox">
                <div class="text-left xCNRptFilter">
                    <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjPosFrom'].' : </span>'.$aDataFilter['tPosCodeFrom'];?></label>
                    <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjPosTo'].' : </span>'.$aDataFilter['tPosCodeTo'];?></label>
                </div>
            </div>
            <?php endif; ?>
            <?php if (isset($aDataFilter['tPosCodeSelect']) && !empty($aDataFilter['tPosCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptPosFrom']; ?> : </span> <?php echo ($aDataFilter['bPosStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tPosCodeSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ============================ ฟิวเตอร์ข้อมูล ลูกค้า ============================ -->
            <?php if ((isset($aDataFilter['tCstCodeFrom']) && !empty($aDataFilter['tCstCodeFrom'])) && (isset($aDataFilter['tCstCodeTo']) && !empty($aDataFilter['tCstCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom']; ?> : </span> <?php echo $aDataFilter['tCstNameFrom']; ?></label>
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstTo']; ?> : </span> <?php echo $aDataFilter['tCstNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($aDataFilter['tCstCodeSelect']) && !empty($aDataFilter['tCstCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom']; ?> : </span> <?php echo ($aDataFilter['bCstStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tCstNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>