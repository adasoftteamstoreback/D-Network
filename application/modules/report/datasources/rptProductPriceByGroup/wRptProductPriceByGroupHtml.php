<?php

$aDataReport    = $aDataViewRpt['aDataReport'];
$aDataTextRef   = $aDataViewRpt['aDataTextRef'];
$aDataFilter    = $aDataViewRpt['aDataFilter'];
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

    .table tbody tr.xCNHeaderGroup,
    .table>tbody>tr.xCNHeaderGroup>td {
        /* color: #232C3D !important; */
        font-size: 18px !important;
        font-weight: 600;
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
        border-bottom: 6px double black !important;
    }

    .xWRptProductFillData>td:first-child {
        text-indent: 40px;
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


<div id="odvRptProductTransferHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <div class="row">
                <?php include 'application\modules\report\datasources\Address\wRptAddress.php'; ?>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
                    <div class="text-center">
                        <label class="xCNRptTitle"><?php echo $aDataTextRef['tTitleReport']; ?></label>
                    </div>

                    <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                    <div class="report-filter">
                        <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) : ?>
                            <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="text-center xCNRptFilter">
                                        <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateFrom']; ?> : </span> <?php echo date('d/m/Y', strtotime($aDataFilter['tDocDateFrom'])); ?></label>
                                        <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateTo']; ?> : </span> <?php echo date('d/m/Y', strtotime($aDataFilter['tDocDateTo'])); ?></label>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ((isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="text-center">
                                        <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom'] ?> : </label> <label><?= $aDataFilter['tBchNameFrom']; ?></label>&nbsp;
                                        <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchTo'] ?> : </label> <label><?= $aDataFilter['tBchNameTo']; ?></label>
                                    </div>
                                </div>
                            </div>
                        <?php }; ?>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 table-responsive">
                        <div class="text-right">
                            <?php date_default_timezone_set('Asia/Bangkok'); ?>
                            <label class="xCNRptDataPrint"><?php echo $aDataTextRef['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $aDataTextRef['tTimePrint'] . ' ' . date('H:i:s'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptAPBGPriceGroup']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptAPBGPrtCode']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptAPBGPrtName']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptAPBGPrtUnit']; ?></th>
                            <th class="text-center xCNRptColumnHeader"><?php echo $aDataTextRef['tRptAPBGDocCode']; ?></th>
                            <th class="text-center xCNRptColumnHeader"><?php echo $aDataTextRef['tRptAPBGDStart']; ?></th>
                            <th class="text-center xCNRptColumnHeader"><?php echo $aDataTextRef['tRptAPBGDStop']; ?></th>
                            <th class="text-center xCNRptColumnHeader"><?php echo $aDataTextRef['tRptAPBGTStart']; ?></th>
                            <th class="text-center xCNRptColumnHeader"><?php echo $aDataTextRef['tRptAPBGTStop']; ?></th>
                            <th class="text-right xCNRptColumnHeader"><?php echo $aDataTextRef['tRptAPBGPrice']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) {  ?>

                                <?php
                                $tRptGtpBchCode = "";
                                $tRptGtpShpCode = "";
                                $tRptGtpWahCode = "";
                                $tRptGtpDocNo   = "";

                                $nCountBchCode  = 0;
                                $nCountShpCode  = 0;
                                $nCountWahCode  = 0;
                                $nCountDocNo    = 0;
                                ?>

                                <?php
                                $tRptFTXshDocNo     = $aValue['FTXphDocNo']; //รหัสเอกสาร
                                $tRptFDXthDocDateStart   = date('d/m/Y', strtotime($aValue["FDXphDStart"]));
                                $tRptFDXthDocDateStop   = date('d/m/Y', strtotime($aValue["FDXphDStop"]));

                                $FTPdtCode = "";
                                $FTPdtName   = "";
                                $FTPunCode  = "";

                                if ($aValue['FTPplName'] == '') {
                                    $aValue['FTPplName'] = 'ไม่กำหนดกลุ่มราคา';
                                }

                                if ($aValue['FTPartFTPplCode'] == 1) {

                                    //เส้น Group

                                    echo "<tr><td class='xCNRptGrouPing' colspan='10' style='border-top: dashed 1px #333 !important;'></td></tr>";


                                    $aGrouppingDataBch  = array($aValue['FTPplName']);
                                    echo "<tr>";
                                    for ($i = 0; $i < FCNnHSizeOf($aGrouppingDataBch); $i++) {
                                        if (strval($aGrouppingDataBch[$i]) != "N") {
                                            echo "<td class='xCNRptGrouPing text-left' style='padding: 5px;' colspan='1'>" . $aGrouppingDataBch[$i] . "</td>";
                                        } else {
                                            echo "<td></td>";
                                        }
                                    }
                                    echo "<tr>";
                                }



                                if ($aValue['FTPartFTPdtCode'] == 1) {
                                    $aGrouppingDataBch  = array($aValue['FTPdtCode']);
                                    for ($i = 0; $i < FCNnHSizeOf($aGrouppingDataBch); $i++) {
                                        if (strval($aGrouppingDataBch[$i]) != "N") {
                                            $FTPdtCode = $aGrouppingDataBch[$i];
                                        } else {
                                            $FTPdtCode = "";
                                        }
                                    }
                                }

                                if ($aValue['FTPartFTPdtName'] == 1) {
                                    $aGrouppingDataBch  = array($aValue['FTPdtName']);
                                    for ($i = 0; $i < FCNnHSizeOf($aGrouppingDataBch); $i++) {
                                        if (strval($aGrouppingDataBch[$i]) != "N") {
                                            $FTPdtName = $aGrouppingDataBch[$i];
                                        } else {
                                            $FTPdtName = "";
                                        }
                                    }
                                }

                                if ($aValue['FTPartFTPunCode'] == 1) {
                                    $aGrouppingDataBch  = array($aValue['FTPunName']);
                                    for ($i = 0; $i < FCNnHSizeOf($aGrouppingDataBch); $i++) {
                                        if (strval($aGrouppingDataBch[$i]) != "N") {
                                            $FTPunCode = $aGrouppingDataBch[$i];
                                        } else {
                                            $FTPunCode = "";
                                        }
                                    }
                                }
                                ?>

                                <tr class="xWRptProductFillData">
                                    <!-- <td class="text-center xCNRptDetail"><?php echo $aValue['FTPplName']; ?></td> -->
                                    <td class="text-left xCNRptDetail"></td>

                                    <td class="text-left xCNRptDetail"><?php echo $FTPdtCode; ?></td>
                                    <td class="text-left xCNRptDetail"><?php echo $FTPdtName; ?></td>
                                    <td class="text-left xCNRptDetail"><?php echo $FTPunCode; ?></td>
                                    <td class="text-center xCNRptDetail"><?php echo $aValue["FTXphDocNo"]; ?></td>
                                    <td class="text-center xCNRptDetail"><?php echo $tRptFDXthDocDateStart; ?></td>
                                    <td class="text-center xCNRptDetail"><?php echo $tRptFDXthDocDateStop; ?></td>
                                    <td class="text-center xCNRptDetail"><?php echo $aValue["FTXphTStart"]; ?></td>
                                    <td class="text-center xCNRptDetail"><?php echo $aValue["FTXphTStop"]; ?></td>
                                    <td class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXpdPriceRet"], 2); ?></td>
                                </tr>



                            <?php  } ?>
                        <?php } else { ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?php echo $aDataTextRef['tRptAdjStkNoData']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php if ((isset($aDataFilter['tWahNameFrom']) && !empty($aDataFilter['tWahNameFrom'])) && (isset($aDataFilter['tWahNameTo']) && !empty($aDataFilter['tWahNameTo']))
                || (isset($aDataFilter['tMerCodeFrom']) && !empty($aDataFilter['tMerCodeFrom'])) && (isset($aDataFilter['tMerCodeTo']) && !empty($aDataFilter['tMerCodeTo']))
                || (isset($aDataFilter['tShpCodeFrom']) && !empty($aDataFilter['tShpCodeFrom'])) && (isset($aDataFilter['tShpCodeTo']) && !empty($aDataFilter['tShpCodeTo']))
                || (isset($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeFrom'])) && (isset($aDataFilter['tPosCodeTo']) && !empty($aDataFilter['tPosCodeTo']))
                || (isset($aDataFilter['tWahCodeFrom']) && !empty($aDataFilter['tWahCodeFrom'])) && (isset($aDataFilter['tWahCodeTo']) && !empty($aDataFilter['tWahCodeTo']))
                || (isset($aDataFilter['tBchCodeSelect']))
                || (isset($aDataFilter['tMerCodeSelect']))
                || (isset($aDataFilter['tShpCodeSelect']))
                || (isset($aDataFilter['tPosCodeSelect']))
                || (isset($aDataFilter['tWahCodeSelect']))
            ) { ?>
                <div class="xCNRptFilterTitle">
                    <div class="text-left">
                        <label class="xCNTextConsOth"><?= $aDataTextRef['tRptConditionInReport']; ?></label>
                    </div>
                </div>
            <?php }; ?>

            <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
            <?php if (isset($aDataFilter['tBchCodeSelect']) && !empty($aDataFilter['tBchCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['bBchStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tBchNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ============================ ฟิวเตอร์ข้อมูล วันที่มีผล ============================ -->
            <?php if (isset($aDataFilter['tEffectiveDateFrom']) && !empty($aDataFilter['tEffectiveDateTo'])) : ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="text-left">
                            <label class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptEffectiveDateFrom')?></label>   <label><?=date('d/m/Y',strtotime($aDataFilter['tEffectiveDateFrom']));?>  </label>&nbsp;
                            <label class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptEffectiveDateTo')?></label>     <label><?=date('d/m/Y',strtotime($aDataFilter['tEffectiveDateTo']));?>    </label>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <!-- ============================ ฟิวเตอร์ข้อมูล สินค้า ============================ -->
            <?php if (isset($aDataFilter['tRptPdtCodeFrom']) && !empty($aDataFilter['tRptPdtCodeTo'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tPdtCodeFrom'); ?> : </span> <?php echo $aDataFilter['tRptPdtNameFrom']; ?> </label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tPdtCodeTo'); ?> : </span> <?php echo $aDataFilter['tRptPdtNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ============================ ฟิวเตอร์ข้อมูล หน่วยสินค้า ============================ -->
            <?php if (isset($aDataFilter['tRptPdtUnitCodeFrom']) && !empty($aDataFilter['tRptPdtUnitCodeTo'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptPdtUnitFrom'); ?> : </span> <?php echo $aDataFilter['tRptPdtUnitNameFrom']; ?> </label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptPdtUnitTo'); ?> : </span> <?php echo $aDataFilter['tRptPdtUnitNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ============================ ฟิวเตอร์ข้อมูล กลุ่มราคาที่มีผล ============================ -->
            <?php if (isset($aDataFilter['tRptEffectivePriceGroupCodeFrom']) && !empty($aDataFilter['tRptEffectivePriceGroupCodeTo'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPplFrom'); ?> : </span> <?php echo $aDataFilter['tRptEffectivePriceGroupNameFrom']; ?> </label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPplTo'); ?> : </span> <?php echo $aDataFilter['tRptEffectivePriceGroupNameTo']; ?></label>
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
