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
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRPC15TBBchCode'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRPC15TBBchName'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRPC12TBPosCode'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptCustName'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSalePendingTel'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptCstLostContLicense'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptGroupRpt07'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRPCCstForCastAddess'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRPCCstForCastEmail'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRPCCstForCastBlueCard'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptCustomerHistoryServiceRegNo'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptDateDocument'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSaleByCashierAndPosPayType'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSBPPrice'); ?></th>
                            <!-- <th nowrap class="text-left xCNRptColumnHeader" >วันที่บริการครั้งล่าสุด</th> -->
                            <!-- <th nowrap class="text-left xCNRptColumnHeader" style=" border-left: 1px dashed black !important;">รหัสสินค้า</th>
                            <th nowrap class="text-left xCNRptColumnHeader" style=" border-left: 1px dashed black !important; border-right: 1px dashed black !important;">ชื่อสินค้า</th> -->
                        </tr>
                        <tr style="border-bottom: 1px solid black !important">
                            <th nowrap class="text-left xCNRptColumnHeader"></th>
                            <th nowrap class="text-left xCNRptColumnHeader"></th>
                            <th nowrap class="text-left xCNRptColumnHeader"></th>
                            <th nowrap class="text-left xCNRptColumnHeader"></th>
                            <th nowrap class="text-left xCNRptColumnHeader"></th>
                            <th nowrap class="text-left xCNRptColumnHeader"></th>
                            <th nowrap class="text-left xCNRptColumnHeader"></th>
                            <th nowrap class="text-left xCNRptColumnHeader"></th>
                            <th nowrap class="text-left xCNRptColumnHeader"></th>
                            <th nowrap class="text-left xCNRptColumnHeader"></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSalByPdtSetPdtCode'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSalByPdtSetPdtName'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"></th>
                            <th nowrap class="text-right xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptCustomerHistoryServiceLastDate'); ?></th>
                            <!-- <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRptTaxSaleFromTo'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:30%;"></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:20%;"><?php echo language('report/report/report', 'tRptTaxSaleFrom'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRptXshDocNo'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRptDate'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRptCodeRef'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRptCodeRefDate'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRptQty2'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRptUnit'); ?></th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php
                            // Set ตัวแปร SumSubFooter และ ตัวแปร SumFooter
                            // $nSubSumAjdWahB4Adj         = 0;
                            // $nSubSumAjdUnitQty          = 0;
                            // $nSubSumAjdQtyAllDiff       = 0;
                            $nSubSumQty = 0;
                            // $nSumFooterAjdWahB4Adj      = 0;
                            // $nSumFooterAjdUnitQty       = 0;
                            // $nSumFooterAjdQtyAllDiff    = 0;
                            $nSumQtyTotal = 0;
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <?php
                                // Step 1 เตรียม Parameter สำหรับการ Groupping
                                // $tDocNo         = $aValue["FTAjhDocNo"];
                                // $tDocDate       = date('d/m/Y h:i:s',strtotime($aValue["FDAjhDocDate"]));
                                // $tApvName       = $aValue["FTAjdApvName"];
                                $tDocDate       = date("d/m/Y", strtotime($aValue['FDXshDocDate']));

                                if (!empty($aValue['FDXshRefIntDate'])) {
                                    $tRefIntDate    = date("d/m/Y", strtotime($aValue['FDXshRefIntDate']));
                                } else {
                                    $tRefIntDate    = "";
                                }

                                if (!empty($aValue['FDFlwLastDate'])) {
                                    $tFlwLastDate       = date("d/m/Y", strtotime($aValue['FDFlwLastDate']));
                                } else {
                                    $tFlwLastDate    = "";
                                }

                                $nGroupMember   = $aValue["FNRptGroupMember"];
                                $nRowPartID     = $aValue["FNRowPartID"];

                                // $tPdtCode =  '$aValue["FTPdtCode"]';
                                // $tPdtName =  '$aValue["FTPdtName"]';
                                ?>

                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                                    <!-- <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"><?php echo $aValue["FTBchCode"]; ?></td> -->
                                    <?php
                                    if ($nRowPartID == 1) {
                                        echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px;'>" . $aValue["FTBchCode"] . "</td>";
                                        echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" . $aValue["FTBchName"] . "</td>";
                                        // echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" . $tDocDate . "</td>";
                                        echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" . $aValue["FTPosCode"] . "</td>";
                                        echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" . $aValue["FTXshCstName"] . "</td>";
                                        echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" . $aValue["FTXshCstTel"] . "</td>";
                                        echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" . $aValue["FTCarRegNo"] . "</td>";
                                        echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" . $aValue["FTCarBrand"] . "</td>";
                                        echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" . $aValue["FTCstAddress"] . "</td>";
                                        echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" . $aValue["FTXshCstEmail"] . "</td>";
                                        echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" . $aValue["FTXshCardNo"] . "</td>";
                                        echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" . $aValue["FTXshDocNo"] . "</td>";
                                        echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" . $tDocDate . "</td>";
                                        // echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; border-left: 1px dashed black !important;'>" . number_format($aValue["FCXrcNet"], $nOptDecimalShow) . "</td>";
                                        // echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; border-left: 1px dashed black !important;'>" . $aValue["FTRcvName"] . "</td>";
                                        // echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; border-left: 1px dashed black !important;'></td>";
                                        // echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; border-left: 1px dashed black !important;'></td>";
                                        // echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; border-left: 1px dashed black !important;'>" . $aValue["FTPdtName"] . "</td>";

                                    } else {
                                        echo "<td class='xCNRptGrouPing' colspan='1' style=''></td>";
                                        echo "<td class='xCNRptGrouPing' colspan='1' style=''></td>";
                                        echo "<td class='xCNRptGrouPing' colspan='1' style=''></td>";
                                        echo "<td class='xCNRptGrouPing' colspan='1' style=''></td>";
                                        echo "<td class='xCNRptGrouPing' colspan='1' style=''></td>";
                                        echo "<td class='xCNRptGrouPing' colspan='1' style='></td>";
                                        echo "<td class='xCNRptGrouPing' colspan='1' style=''></td>";
                                        echo "<td class='xCNRptGrouPing' colspan='1' style=''></td>";
                                        echo "<td class='xCNRptGrouPing' colspan='1' style=''></td>";
                                        echo "<td class='xCNRptGrouPing' colspan='1' style=''></td>";
                                        echo "<td class='xCNRptGrouPing' colspan='1' style=''></td>";
                                        echo "<td class='xCNRptGrouPing' colspan='1' style=''>" . $aValue["FTPdtCode"] . "</td>";
                                        echo "<td class='xCNRptGrouPing' colspan='1' style=''>" . $aValue["FTPdtName"] . "</td>";
                                        // echo "<td class='xCNRptGrouPing' colspan='1' style=' border-left: 1px dashed black !important;'></td>";
                                        // echo "<td class='xCNRptGrouPing' colspan='1' style=' border-left: 1px dashed black !important;'></td>";
                                        // echo "<td class='xCNRptGrouPing' colspan='1' style=' border-left: 1px dashed black !important;'></td>";
                                        // echo "<td class='xCNRptGrouPing' colspan='1' style=' border-left: 1px dashed black !important;'></td>";
                                        // echo "<td class='xCNRptGrouPing' colspan='1' style=' border-left: 1px dashed black !important;'></td>";

                                    }
                                    ?>
                                    <!-- <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"><?php echo $aValue["FTBchName"]; ?></td> -->
                                    <!-- <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"><?php echo $tDocDate; ?></td> -->
                                    <!-- <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"></td> -->
                                    <!-- <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"><?php echo $aValue["FTXshCstName"]; ?></td> -->
                                    <!-- <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"><?php echo $aValue["FTXshCstTel"]; ?></td> -->
                                    <!-- <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"><?php echo $aValue["FTAddV1No"]; ?></td> -->
                                    <!-- <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"><?php echo $aValue["FTCarBrand"]; ?></td>
                                    <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"><?php echo $aValue["FTCstAddress"]; ?></td>
                                    <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"><?php echo $aValue["FTXshCstEmail"]; ?></td>
                                    <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"><?php echo $aValue["FTXshCardNo"]; ?></td> -->
                                    <?php
                                    // if ($nRowPartID == 1) {
                                    //     echo "<td class='xCNRptGrouPing  text-left' style='border-top: dashed 1px #333 !important; padding: 5px; border-left: 1px dashed black !important;'>" . $aValue["FTXshDocNo"] . "</td>";
                                    // } else {
                                    //     echo "<td class='xCNRptGrouPing' colspan='1' style=' border-left: 1px dashed black !important;'></td>";
                                    // }
                                    ?>
                                    <!-- <td class="text-right xCNRptDetail" style=""><?php echo number_format($aValue["FCXrcNet"], $nOptDecimalShow); ?></td> -->
                                    <!-- <td class="text-left xCNRptDetail text-left" style="border-top: dashed 1px #333 !important; padding: 5px;"><?php echo $aValue["FTRcvName"]; ?></td>  -->
                                    <!-- <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"></td> -->

                                    <?php
                                    if ($nRowPartID == 1) {
                                        echo "<td class='xCNRptGrouPing  text-left' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" .  $aValue["FTRcvName"] . "</td>";
                                        echo "<td class='xCNRptGrouPing  text-right' style=' border-top: dashed 1px #333 !important; padding: 5px; '>" . number_format($aValue["FCXrcNet"], $nOptDecimalShow) . "</td>";
                                    } else {
                                        if ($aValue["FNTxnType"] == 1) {
                                            echo "<td class='xCNRptGrouPing  text-left' style='padding: 5px; '>" .  $aValue["FTRcvName"] . "</td>";
                                            echo "<td class='xCNRptGrouPing  text-right' style='padding: 5px; '>" . number_format($aValue["FCXrcNet"], $nOptDecimalShow) . "</td>";
                                        } else {
                                            echo "<td class='xCNRptGrouPing' colspan='1' style=''></td>";
                                            echo "<td class='xCNRptGrouPing text-right'  colspan='1' style=''>" . $tFlwLastDate . "</td>";
                                        }
                                    }
                                    ?>
                                    <!-- <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"></td>
                                    <td class="text-left xCNRptDetail" style=" border-left: 1px dashed black !important; border-right: 1px dashed black !important; border-bottom: dashed 1px #333 !important;"><?php echo $aValue["FTPdtName"]; ?></td> -->
                                </tr>

                                <?php
                                // Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer
                                // $nSubSumAjdWahB4Adj     = intval($aValue["FCAjdWahB4Adj_SubTotal"]);
                                // $nSubSumAjdUnitQty      = intval($aValue["FCAjdUnitQty_SubTotal"]);
                                // $nSubSumAjdQtyAllDiff   = intval($aValue["FCAjdQtyAllDiff_SubTotal"]);
                                // $nSubSumQty  = number_format($aValue["FCXidQty_SubFooter"], $nOptDecimalShow);
                                // $nSubSumQty  = '';

                                // Step 4 : สั่ง Summary SubFooter
                                // Parameter
                                // $nGroupMember     = จำนวนข้อมูลทั้งหมดในกลุ่ม
                                // $nRowPartID       = ลำดับข้อมูลในกลุ่ม
                                // $aSumFooter       =  ข้อมูล Summary SubFooter
                                // $aSumFooter             = array($aDataTextRef['tRptAdjStkVDTotalSub'], 'N', 'N', 'N', 'N', 'N', 'N', $nSubSumQty, 'N');
                                // if ($nRowPartID == $nGroupMember) {
                                //     echo '<tr>';
                                //     for ($i = 0; $i < FCNnHSizeOf($aSumFooter); $i++) {
                                //         if (strval($aSumFooter[$i]) != "N") {
                                //             $tFooterVal = $aSumFooter[$i];
                                //         } else {
                                //             $tFooterVal = '';
                                //         }

                                //         if ($i == 7) {
                                //             $tClassCss = "text-right";
                                //         } else {
                                //             $tClassCss = "text-left";
                                //         }
                                //         echo "<td class='xCNRptGrouPing $tClassCss'  style='border-top: dashed 1px #333 !important;'>" . $tFooterVal . "</td>";
                                //     }
                                //     echo "</tr>";

                                //     $nCountDataAll = FCNnHSizeOf($aDataReport['aRptData']);
                                //     if ($nCountDataAll - 1 != $nKey) {
                                //         echo "<tr><td class='xCNRptGrouPing' colspan='9' style='border-top: dashed 1px #333 !important;'></td></tr>";
                                //     }
                                // }

                                // Step 5 เตรียม Parameter สำหรับ SumFooter
                                // $nSumFooterAjdWahB4Adj      = intval($aValue["FCAjdWahB4Adj_Footer"]);
                                // $nSumFooterAjdUnitQty       = intval($aValue["FCAjdUnitQty_Footer"]);
                                // $nSumFooterAjdQtyAllDiff    = intval($aValue["FCAjdQtyAllDiff_Footer"]);
                                $nSumQtyTotal  = number_format($aValue["FCXidQty_Footer"], $nOptDecimalShow);
                                // $nSumQtyTotal  = '';
                                $aFooterSumData             = array($aDataTextRef['tRptAdjStkVDTotalFooter'], 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', $nSumQtyTotal);
                                ?>
                            <?php endforeach; ?>
                            <tr class="text-left xCNRptDetail" style=" border-left: 17px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                            <?php
                            //Step 6 : สั่ง Summary Footer
                            $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                            if ($nPageNo == $nTotalPage) {
                                echo "<tr class='xCNTrFooter'>";
                                for ($i = 0; $i < FCNnHSizeOf($aFooterSumData); $i++) {
                                    if ($i == 0) {
                                        $tStyle = 'text-align:left;border-top:1px solid #333;border-bottom:1px solid #333;/*background-color: #CFE2F3;*/';
                                    } else {
                                        $tStyle = 'text-align:right;border-top:1px solid #333;border-bottom:1px solid #333;/*background-color: #CFE2F3;*/';
                                    }

                                    if (strval($aFooterSumData[$i]) != "N") {
                                        $tFooterVal = $aFooterSumData[$i];
                                    } else {
                                        $tFooterVal = '';
                                    }
                                    if ($i == 0) {
                                        echo "<td class='xCNRptSumFooter text-left' colspan='1'>" . $tFooterVal . "</td>";
                                    } else {
                                        echo "<td class='xCNRptSumFooter text-right'>" . $tFooterVal . "</td>";
                                    }
                                }
                                echo "<tr>";
                            }
                            ?>
                        <?php else : ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?php echo $aDataTextRef['tRptAdjStkNoData']; ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ((isset($aDataFilter['tMerCodeFrom']) && !empty($aDataFilter['tMerCodeFrom'])) && (isset($aDataFilter['tMerCodeTo']) && !empty($aDataFilter['tMerCodeTo']))
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


            <?php if ((isset($aDataFilter['tCstCodeFrom']) && !empty($aDataFilter['tCstCodeFrom'])) && (isset($aDataFilter['tCstCodeTo']) && !empty($aDataFilter['tCstCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ลูกค้า =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom'] . ' : </span>' . $aDataFilter['tCstCodeFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstTo'] . ' : </span>' . $aDataFilter['tCstCodeTo']; ?></label>
                    </div>
                </div>
            <?php }; ?>

            <?php if ((isset($aDataFilter['tRegCodeFrom']) && !empty($aDataFilter['tRegCodeFrom'])) && (isset($aDataFilter['tRegCodeTo']) && !empty($aDataFilter['tRegCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ทะเบียนรถ =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptRegCodeFrom'] . ' : </span>' . $aDataFilter['tRegCodeFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptRegCodeTo'] . ' : </span>' . $aDataFilter['tRegCodeTo']; ?></label>
                    </div>
                </div>
            <?php }; ?>

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