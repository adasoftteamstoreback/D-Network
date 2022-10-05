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

    /*แนวนอน*/
    @media print {
        @page {
            size: A4 landscape;
        }

        body {
            margin: 0;
            color: #000;
            background-color: #fff;
        }

    }

    /*แนวตั้ง*/
    /*@media print{@page {size: portrait}}*/
</style>
<div id="odvRptTaxSalePosHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <!-- <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center">
                        <label class="xCNRptTitle"><?php echo $aDataTextRef['tTitleReport']; ?></label>
                    </div>
                </div>
            </div> -->
            <div class="row">
                <?php include 'application\modules\report\datasources\Address\wRptAddress.php'; ?>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="text-center">
                        <label class="xCNRptTitle"><?php echo $aDataTextRef['tTitleReport']; ?></label>
                    </div>
                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) : ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSaleByCashierAndPosFilterDocDateFrom']; ?> </span> <?php echo date("d/m/Y", strtotime($aDataFilter['tDocDateFrom'])); ?></label>
                                    <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSaleByCashierAndPosFilterDocDateTo']; ?> </span> <?php echo date("d/m/Y", strtotime($aDataFilter['tDocDateTo'])); ?></label>
                                </div>
                                <?php
                                //   //จัดฟอร์แมต DateFrom
                                //   $tDocDateFrom = explode("-",$aDataFilter['tDocDateFrom']);
                                //   //เซตปี คศ +543
                                //   $tYearFrom    = $tDocDateFrom[0]+543;
                                //   $tMonthFrom   = $tDocDateFrom[1];
                                //   $tDayFrom     = $tDocDateFrom[2];
                                //   //ตรวจสอบ เดือนที่เลือก
                                //   $tMonth  = language('report/report/report', 'tRptMonth'.$tMonthFrom);
                                //   //จัดฟอแมต วัน/เดือน/ปี
                                //   $tFormatDateFrom =($aDataTextRef['tRptTaxSalePosDocDate'].' '.$tDayFrom.' '.$aDataTextRef['tRptTaxSalePosTaxMonth'].' '.$tMonth.' '.$aDataTextRef['tRptTaxSalePosYear'].' '.$tYearFrom);
                                ?>
                                <?php
                                //   ////จัดฟอร์แมต DateTo
                                //   $tDocDateFrom = explode("-",$aDataFilter['tDocDateTo']);
                                //   //เซตปี คศ +543
                                //   $tYearFromTo    = $tDocDateFrom[0]+543;
                                //   $tMonthFromTo   = $tDocDateFrom[1];
                                //   $tDayFromTo     = $tDocDateFrom[2];
                                //   //ตรวจสอบ เดือนที่เลือก
                                //   $tMonthFormTo  = language('report/report/report', 'tRptMonth'.$tMonthFromTo);
                                //   //จัดฟอแมต วัน/เดือน/ปี
                                //   $tFormatDateFromTo =($aDataTextRef['tRptTaxSalePosDocDate'].' '.$tDayFromTo.' '.$aDataTextRef['tRptTaxSalePosTaxMonth'].' '.$tMonthFormTo.' '.$aDataTextRef['tRptTaxSalePosYear'].' '.$tYearFromTo);
                                ?>
                                <!-- <div class="text-center xCNRptFilter">
                                    <label><?php echo $tFormatDateFrom; ?></label>&nbsp;&nbsp;
                                    <label><?php echo $aDataTextRef['tRptTaxSaleDateTo'] . ' ' . $tFormatDateFromTo; ?></label>
                                </div> -->
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ((isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptTaxSalePosFilterBchFrom'] . ' </span>' . $aDataFilter['tBchNameFrom']; ?></label>&nbsp;&nbsp;
                                    <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptTaxSalePosFilterBchTo'] . ' </span>' . $aDataFilter['tBchNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php }; ?>

                    <!-- <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center xCNRptFilter">
                                <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptTaxSalePosType'] . ' : ' . language('report/report/report', 'tRptPosType' . $aDataFilter['tPosType']); ?></label>
                            </div>
                        </div>
                    </div> -->

                </div>

                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 ">

                </div>
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
                            <th nowrap class="text-left xCNRptColumnHeader" style="vertical-align : middle;text-align:left; width:5%;" rowspan="2"><?php echo $aDataTextRef['tRptTaxSalePosSeq']; ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="vertical-align : middle;text-align:center; width:5%;" rowspan="2"><?php echo $aDataTextRef['tRptTaxSalePosDocDate']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="vertical-align : middle;text-align:left; width:5%;" rowspan="2"><?php echo $aDataTextRef['tRptPurVatTaxNoDoc']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="vertical-align : middle;text-align:left; width:5%;" rowspan="2"><?php echo $aDataTextRef['tRptTaxSalePosDocRef']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="vertical-align : middle;text-align:left; width:10%;" rowspan="2"><?php echo $aDataTextRef['tRptXshDocNo']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="vertical-align : middle;text-align:left; width:15%;" rowspan="2"><?php echo $aDataTextRef['tRptDocPdtTwiSrcTypeAgn']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="vertical-align : middle;text-align:left; width:20%;" rowspan="2"><?php echo $aDataTextRef['tRptPurVatTaxNo']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="vertical-align : middle;text-align:left; width:20%;" rowspan="2"><?php echo $aDataTextRef['tRptTaxSalePosComp']; ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="vertical-align : middle;text-align:right; width:5%;"><?php echo $aDataTextRef['tRptValue']; ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="vertical-align : middle;text-align:right; width:5%;"><?php echo $aDataTextRef['tRptTaxSalePosAmtV']; ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="vertical-align : middle;text-align:right; width:5%;"><?php echo $aDataTextRef['tRptTaxSalePosAmtNV']; ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="vertical-align : middle;text-align:right; width:5%;"><?php echo $aDataTextRef['tRptTaxSalePosTotalSub']; ?></th>
                        </tr>



                    </thead>
                    <tbody>

                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php
                            $paFooterSumData1   = 0;
                            $paFooterSumData2   = 0;
                            $paFooterSumData3   = 0;
                            $nSeq               = "";
                            $tGrouppingBch      = "";
                            $tPosCodeSub        = "";
                            $nCountBch          = 0;
                            $nPosCodeOld        = "";
                            //print_r($aDataReport['aRptData']);
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <?php
                                // Step 1 เตรียม Parameter สำหรับการ Groupping
                                $tXrcNet = 0;
                                $tDocNo   = $aValue["FTXphDocNo"];
                                // $tPosCode = $aValue["FTPosCode"];
                                $tBchCode = $aValue["FTBchCode"];
                                $tBchName = $aValue["FTBchName"];
                                // $tPosRegNo = $aValue["FTPosRegNo"];
                                $tDocDate = date("d/m/Y", strtotime($aValue['FDXphRefExtDate']));
                                $nRowPartID = $aValue["FNRowPartID"];
                                $nGroupMember = $aValue['FNRptGroupMember'];

                                $nBchXphAmt =  number_format($aValue["FCXphAmt_SUMBCH"], $nOptDecimalShow);
                                $nBchXphVat =  number_format($aValue["FCXphVat_SUMBCH"], $nOptDecimalShow);
                                $nBchXphAmtNV =  number_format($aValue["FCXphAmtNV_SUMBCH"], $nOptDecimalShow);
                                $nBchXphGrandTotal =  number_format($aValue["FCXphGrandTotal_SUMBCH"], $nOptDecimalShow);

                                $nPosXphAmt =  number_format($aValue["FCXphAmt_SUMPOS"], $nOptDecimalShow);
                                $nPosXphVat =  number_format($aValue["FCXphVat_SUMPOS"], $nOptDecimalShow);
                                $nPosXphAmtNV =  number_format($aValue["FCXphAmtNV_SUMPOS"], $nOptDecimalShow);
                                $nPosXphGrandTotal =  number_format($aValue["FCXphGrandTotal_SUMPOS"], $nOptDecimalShow);

                                ?>
                                <?php
                                // Step 2 Groupping data
                                // $aGrouppingDataBch = array($aDataTextRef['tRptTaxSalePosBch'] .'('.$tBchCode. ')' ,  'N', 'N', 'N', 'N','N','N', $nXphAmt, $nXphVat, $nXphAmtNV , $nXphGrandTotal_SumSup);
                                $aGrouppingDataBch = array($aDataTextRef['tRptTaxSalePosBch'] . '(' . $tBchCode . ') ' .  $tBchName,   ' ', ' ',   ' ',   ' ',   ' ', $nBchXphAmt, $nBchXphVat, $nBchXphAmtNV, $nBchXphGrandTotal);
                                // $aGrouppingData    = array($aDataTextRef['tRptTaxSalePosSale'] . ' ' . '  PID : ' . ' ', ' ', ' ', ' ', $nPosXphAmt, $nPosXphVat, $nPosXphAmtNV, $nPosXphGrandTotal);
                                /*Parameter
                                    $nRowPartID      = ลำดับตามกลุ่ม

                                    $aGrouppingDataBch  = ข้อมูลสำหรับ Groupping สาขา*/
                                if ($tGrouppingBch == $tBchCode && $aValue['FNRowPartID'] == 1) {
                                    // $tSumFooter         = array('N','N','N','N','N','N','N','N','N','N','N');
                                    // if($nRowPartID == $nGroupMember){
                                    //     echo '<tr>';
                                    //     for($i = 0;$i<FCNnHSizeOf($tSumFooter);$i++){
                                    //         if($tSumFooter[$i] !='N'){
                                    //             $tFooterVal =   $tSumFooter[$i];
                                    //         }else{
                                    //             $tFooterVal =   '';
                                    //         }
                                    //             echo "<td class='xCNRptGrouPing'  style='border-bottom: dashed 1px #333 !important;'>".$tFooterVal."</td>";
                                    //     }
                                    //     echo '</tr>';
                                    // }
                                }

                                if ($tGrouppingBch != $tBchCode && $nCountBch > 0) {
                                    $tSumFooter         = array('N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
                                    echo "<tr><td class='xCNRptGrouPing' colspan='12' style='border-bottom: dashed 1px #333 !important;'></td></tr>";
                                    if ($nRowPartID == $nGroupMember) {
                                        echo '<tr>';
                                        for ($i = 0; $i < FCNnHSizeOf($tSumFooter); $i++) {
                                            if ($tSumFooter[$i] != 'N') {
                                                $tFooterVal =   $tSumFooter[$i];
                                            } else {
                                                $tFooterVal =   '';
                                            }
                                            // echo "<td class='xCNRptGrouPing'  style='border-bottom: dashed 1px #333 !important;'>" . $tFooterVal . "</td>";
                                        }
                                        echo '</tr>';
                                    }
                                }


                                if ($tGrouppingBch != $tBchCode) {
                                    FCNtHRPTHeadGrouppingRptTSPBch($nRowPartID, $aGrouppingDataBch);
                                    if ($nRowPartID == 1) {
                                        // echo "<tr><td class='xCNRptGrouPing' colspan='11' style='border-bottom: dashed 1px #333 !important;'></td></tr>";
                                    }
                                    $tGrouppingBch = $tBchCode;
                                    $nCountBch++;
                                    $nSeq    = 1;
                                    $nPosCodeOld = '';
                                }

                                //เรียงตาม pos ใหม่ - supawat 06/01/2020
                                // if ($nPosCodeOld == $aValue["FTBchCode"]) {
                                //     // echo '<tr><td>ซ้ำ</td></tr>';
                                // } else {
                                //     echo "<tr><td class='xCNRptGrouPing' colspan='11' style='border-bottom: dashed 1px #333 !important;'></td></tr>";
                                //     echo "<tr>";
                                //     for ($i = 0; $i < FCNnHSizeOf($aGrouppingData); $i++) {
                                //         //echo '<td>'.$nPosXphAmt.'</td>';
                                //         if ($aGrouppingData[$i] == $aGrouppingData[0]) {
                                //             echo "<td class='xCNRptGrouPing' colspan='4' style='padding: 5px; text-indent:22px;'>" . $aGrouppingData[$i] . "</td>";
                                //         } else {
                                //             echo "<td class='xCNRptGrouPing text-right'  style='padding: 5px; text-indent:22px;'>" . $aGrouppingData[$i] . "</td>";
                                //         }
                                //     }
                                //     echo "</tr>";
                                //     $nPosCodeOld = $aValue["FTBchCode"];
                                //     $nSeq    = 1;
                                // }

                                // if($aValue["FNRowPartID"] == 1){
                                //     //FCNtHRPTHeadGrouppingRptTSPPos($nRowPartID, $aGrouppingData);
                                //     $nSeq    = 1;
                                // }
                                ?>
                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                                    <td class="text-left xCNRptDetail" style="text-indent:22px;"><?php echo $nSeq++; ?></td>
                                    <td class="text-center xCNRptDetail"><?php echo substr($aValue['FDXphDocDate'], 0, 10); ?></td>
                                    <td class="text-left xCNRptDetail"><?php echo $aValue['FTXphDocNo']; ?></td>
                                    <td class="text-left xCNRptDetail"><?php echo $aValue["FTXphDocRef"]; ?></td>
                                    <td class="text-left xCNRptDetail"><?php echo $aValue["FTXphDocNo"]; ?></td>


                                    <td class="text-left xCNRptDetail">
                                        <?php if ($aValue["FTSplCode"] != '') {
                                            echo '(' . $aValue["FTSplCode"] . ')';
                                        } else {
                                            echo language('report/report/report', 'tRptCstNormal');
                                        }
                                        ?>
                                        <?php if ($aValue["FTSplName"] != '') {
                                            echo $aValue["FTSplName"];
                                        } else {
                                            echo '';
                                        }
                                        ?>
                                    </td>

                                    <td class="text-left xCNRptDetail">
                                        <?php if ($aValue["FTSplTaxNo"] != '') {
                                            echo $aValue["FTSplTaxNo"];
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-left xCNRptDetail"><?php echo $aValue["FTEstablishment"]; ?></td>
                                    <td class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXphAmt"], $nOptDecimalShow); ?></td>
                                    <td class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXphVat"], $nOptDecimalShow); ?></td>
                                    <td class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXphAmtNV"], $nOptDecimalShow); ?></td>
                                    <td class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXphGrandTotal"], $nOptDecimalShow); ?></td>

                                </tr>
                                <?php
                                // Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer
                                // $aSumFooter1         = array('N','N', 'เงินสด', number_format($aValue["rcCash"], 2));
                                // $aSumFooter2         = array('N','N', 'บัตรเครดิต', number_format($aValue["rcCredit"], 2));

                                // Step 4 : สั่ง Summary Sub Footer
                                // Parameter
                                // $nGroupMember     = จำนวนข้อมูลทั้งหมดในกลุ่ม
                                // $nRowPartID       = ลำดับข้อมูลในกลุ่ม


                                $tPosCodeSub = $aValue["FTBchCode"];

                                // Step 5 เตรียม Parameter สำหรับ SumFooter
                                $paFooterSumData = array($aDataTextRef['tRptTaxSalePosByDateTotalSub'], 'N', 'N', 'N', 'N', 'N', 'N', 'N', number_format(@$aValue['FCXphAmt_Footer'], $nOptDecimalShow), number_format(@$aValue['FCXphVat_Footer'], $nOptDecimalShow), number_format(@$aValue['FCXphAmtNV_Footer'], $nOptDecimalShow), number_format(@$aValue['FCXphGrandTotal_Footer'], $nOptDecimalShow));
                                ?>
                            <?php } ?>
                            <?php
                            // Step 6 : สั่ง Summary Footer
                            $nPageNo = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                            FCNtHRPTSumFooter($nPageNo, $nTotalPage, $paFooterSumData);
                            ?>
                        <?php } else { ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?php echo $aDataTextRef['tRptTaxSalePosNoData']; ?></td>
                            </tr>
                        <?php }; ?>
                    </tbody>
                </table>
            </div>

            <!--เเสดงหน้า-->

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

            <!-- ============================ ฟิวเตอร์ข้อมูล กลุ่มธุรกิจ ============================ -->
            <?php if ((isset($aDataFilter['tMerCodeFrom']) && !empty($aDataFilter['tMerCodeFrom'])) && (isset($aDataFilter['tMerCodeTo']) && !empty($aDataFilter['tMerCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjMerChantFrom'] . ' : </span>' . $aDataFilter['tMerNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjMerChantTo'] . ' : </span>' . $aDataFilter['tMerNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($aDataFilter['tMerCodeSelect']) && !empty($aDataFilter['tMerCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptMerFrom']; ?> : </span> <?php echo ($aDataFilter['bMerStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tMerNameSelect']; ?></label>
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
