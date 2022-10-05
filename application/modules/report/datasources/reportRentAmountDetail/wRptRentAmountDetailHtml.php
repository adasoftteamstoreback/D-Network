<?php
    $aDataReport = $aDataViewRpt['aDataReport'];
    $aDataTextRef = $aDataViewRpt['aDataTextRef'];
    $aDataFilter = $aDataViewRpt['aDataFilter'];
?>
<style>
    /** Set Media Print */
    @media print{@page {size: A4 landscape;}}

    .xCNFooterRpt {
        border-bottom : 7px double #ddd;
    }

    .table thead th, .table>thead>tr>th, .table tbody tr, .table>tbody>tr>td {
        border: 0px transparent !important;
    }

    .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
        border-top: 1px solid black !important;
        border-bottom : 1px solid black !important;
    }


</style>
<div id="odvRptRentAmountDetailHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">        
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <?php if (isset($aCompanyInfo) && !empty($aCompanyInfo)):?>
                        <div class="text-left">
                            <label class="xCNRptCompany"><?php echo @$aCompanyInfo['FTCmpName']?></label>
                        </div>
                        <?php if(isset($aCompanyInfo['FTAddVersion']) && $aCompanyInfo['FTAddVersion'] == '1') { // ที่อยู่แบบแยก ?>
                            <div class="text-left xCNRptAddress">
                                <label ><?php echo @$aCompanyInfo['FTAddV1No'] . ' ' . @$aCompanyInfo['FTAddV1Road'] . ' ' . @$aCompanyInfo['FTAddV1Soi']?>
                                <?php echo @$aCompanyInfo['FTSudName'] . ' ' . @$aCompanyInfo['FTDstName'] . ' ' . @$aCompanyInfo['FTPvnName'] . ' ' . @$aCompanyInfo['FTAddV1PostCode']?></label>
                            </div>
                        <?php } ?>
                        <?php if(isset($aCompanyInfo['FTAddVersion']) && $aCompanyInfo['FTAddVersion'] == '2') { // ที่อยู่แบบรวม ?>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?php echo @$aCompanyInfo['FTAddV2Desc1'] ?></label>
                            </div>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?php echo @$aCompanyInfo['FTAddV2Desc2'] ?></label>
                            </div>
                        <?php } ?>
                        <div class="text-left xCNRptAddress">
                            <label><?php echo @$aDataTextRef['tRptAddrTel'] . @$aCompanyInfo['FTCmpTel']?> <?php echo @$aDataTextRef['tRptAddrFax'] . @$aCompanyInfo['FTCmpFax']?></label>
                        </div>
                        <div class="text-left xCNRptAddress">
                            <label><?php echo @$aDataTextRef['tRptAddrBranch'] . @$aCompanyInfo['FTBchName'];?></label>
                        </div>
                        <div class="text-left xCNRptAddress">
                            <label><?php echo @$aDataTextRef['tRptAddrTaxNo'] . @$aCompanyInfo['FTAddTaxNo']?></label>
                        </div> 
                    <?php endif;?>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center">
                                <label class="xCNRptTitle"><?php echo @$aDataTextRef['tTitleReport']; ?></label>
                            </div>
                        </div>
                    </div>
                    <?php if((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล กลุ่มช่อง ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label><?php echo $aDataTextRef['tRptDateFrom'].' '.$aDataFilter['tDocDateFrom'];?></label>&nbsp;&nbsp;
                                    <label><?php echo $aDataTextRef['tRptDateTo'].' '.$aDataFilter['tDocDateTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if((isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label><?php echo $aDataTextRef['tRptBchFrom'].' '.$aDataFilter['tBchNameFrom'];?></label>&nbsp;&nbsp;
                                    <label><?php echo $aDataTextRef['tRptBchTo'].' '.$aDataFilter['tBchNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <label class="xCNRptDataPrint"><?php echo @$aDataTextRef['tDatePrint'].' '.date('d/m/Y').' '.@$aDataTextRef['tTimePrint'].' '.date('H:i:s');?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptRentAmtForDetailSerailPos']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptRentAmtForDetailUser']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptRentAmtForDetailDocno']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptRentAmtForDetailDocDate']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptRentAmtForDetailRack']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptRentAmtForDetailDateGet']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptRentAmtForDetailLoginTo']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptRentAmtForDetailStaPayment']; ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo $aDataTextRef['tRptRentAmtForDetailAmtPayment']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        // echo "<pre>";
                        // print_r ($aDataReport);
                        // echo "</pre>";
                        // exit;
                            $aPagination    = $aDataReport["aPagination"];
                        ?>
                        <?php if (!empty($aDataReport["aRptData"])) { ?>
                            <?php foreach ($aDataReport["aRptData"]['aData'] as $aDataItems) { ?>
                                        <tr>
                                            <?php
                                                if ($aDataItems['PARTITIONBYPOS'] == 1 || $aDataItems['PARTITIONBYPOS2'] == 1) {
                                                    $tPosCode = $aDataItems["FTPosCode"];
                                                }else {
                                                    $tPosCode = '';
                                                }
                                            ?>
                                            <td nowrap class="text-left xCNRptDetail" style="width:10%">
                                                <?php echo $tPosCode; ?>
                                            </td>
                                            <td nowrap class="text-left xCNRptDetail" style="width:10%">
                                                <?php echo $aDataItems["FTXshFrmLogin"]; ?>
                                            </td>
                                            <td nowrap class="text-left xCNRptDetail" style="width:10%">
                                                <?php echo $aDataItems["FTXshDocNo"]; ?>
                                            </td>
                                            <td nowrap class="text-left xCNRptDetail" style="width:10%">
                                                <?php echo date("Y/m/d", strtotime($aDataItems["FDXshDocDate"])); ?>
                                            </td>
                                            <td nowrap class="text-left xCNRptDetail" style="width:10%">
                                                <?php echo $aDataItems["FTRakName"]; ?>
                                            </td>
                                            <td nowrap class="text-left xCNRptDetail" style="width:10%">
                                                <?php echo date("Y/m/d", strtotime($aDataItems["FDXshDatePick"])); ?>
                                            </td>
                                            <td nowrap class="text-left xCNRptDetail" style="width:10%">
                                                <?php echo $aDataItems["FTXshToLogin"]; ?>
                                            </td>
                                            <td nowrap class="text-left xCNRptDetail" style="width:10%">
                                                <?php
                                                    if ($aDataItems["FTXshStaPaid"] == 1) {
                                                        echo $aDataTextRef['tRptRentAmtForDetailStaPaymentNoPay'];
                                                    } else if ($aDataItems["FTXshStaPaid"] == 2) {
                                                        echo $aDataTextRef['tRptRentAmtForDetailStaPaymentSome'];
                                                    }if ($aDataItems["FTXshStaPaid"] == 3) {
                                                        echo $aDataTextRef['tRptRentAmtForDetailStaPaymentAlready'];
                                                    }
                                                ?>
                                            </td>
                                            <td nowrap class="text-right xCNRptDetail" style="width:10%">
                                                <?php
                                                    if ($aDataItems["FCXshPrePaid"] == "") {
                                                        echo number_format("0", $nOptDecimalShow);
                                                    } else {
                                                        echo number_format($aDataItems["FCXshPrePaid"], $nOptDecimalShow);
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                
                                      
                                <?php if ($aDataItems["PARTITIONBYPOS"] == $aDataItems["COUNTBYPOS"]) { ?>
                                    <?php foreach ($aDataReport["aRptData"]['aSumData'] as $aSumDataItems) { ?>  
                                        <?php if ($aSumDataItems["FNXshNumDoc"] == $aDataItems["COUNTBYPOS"]) { ?>
                                        <tr>
                                            <td nowrap colspan="8" class="text-left xCNRptDetail" style="border-top:1px solid #333 !important;border-bottom:1px solid #333 !important;">
                                                <?php echo @$aDataTextRef['tRptRentAmtForDetailSumText']; ?>
                                            </td>
                                            <td nowrap class="text-right xCNRptDetail" style="border-top:1px solid #333 !important;border-bottom:1px solid #333 !important;">
                                                <?php echo number_format($aSumDataItems["FCXshSumPrePaid"], $nOptDecimalShow); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                                <?php if ($aPagination["nTotalPage"] == $aPagination["nDisplayPage"]) { ?>
                                    <tr class="xCNTrFooter">
                                        <td nowrap colspan="8" class="text-left xCNRptDetail" style="border-top:1px solid #333 !important;border-bottom:1px solid #333 !important;">
                                            <?php echo @$aDataTextRef['tRptRentAmtForDetailSumTextLast']; ?>
                                        </td>
                                        <td nowrap class="text-right xCNRptDetail" style="border-top:1px solid #333 !important;border-bottom:1px solid #333 !important;">
                                            <?php echo number_format($aDataReport["aRptData"]['aSumData'][0]['FCXshSumAllPrePaid'], $nOptDecimalShow); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                        <?php } else { ?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo @$aDataTextRef['tRptAdjStkNoData']; ?></td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php
                $nPageNo    =   $aPagination["nDisplayPage"];
                $nTotalPage    =   $aPagination["nTotalPage"];
            ?>
            <?php if($nPageNo == $nTotalPage): ?>
                <div class="xCNRptFilterTitle">
                    <label><u><?php echo @$aDataTextRef['tRptConditionInReport']; ?></u></label>
                </div>

                <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                <?php if (isset($aDataFilter['tBchCodeSelect']) && !empty($aDataFilter['tBchCodeSelect'])) : ?>
                    <div class="xCNRptFilterBox">
                        <div class="xCNRptFilter">
                            <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['bBchStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tBchNameSelect']; ?></label>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- ============================ ฟิวเตอร์ข้อมูล ร้านค้า ============================ -->
                <?php if (isset($aDataFilter['tShpCodeSelect']) && !empty($aDataFilter['tShpCodeSelect'])) : ?>
                    <div class="xCNRptFilterBox">
                        <div class="xCNRptFilter">
                            <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptShopFrom']; ?> : </span> <?php echo $aDataFilter['tShpNameSelect']; ?></label>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- ============================ ฟิวเตอร์ข้อมูล จุดขาย ============================ -->
                <?php if (isset($aDataFilter['tPosCodeSelect']) && !empty($aDataFilter['tPosCodeSelect'])) : ?>
                    <div class="xCNRptFilterBox">
                        <div class="xCNRptFilter">
                            <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report','tRptLockerBookingPosForm'); ?> : </span> <?php echo $aDataFilter['tPosNameSelect']; ?></label>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( (isset($aDataFilter['tRackCodeFrom']) && !empty($aDataFilter['tRackCodeFrom'])) && (isset($aDataFilter['tRackCodeTo']) && !empty($aDataFilter['tRackCodeTo']))): ?>
                    <!-- ============================ ฟิวเตอร์ข้อมูล กลุ่มช่อง ============================ -->
                    <div class="xCNRptFilterBox">
                        <div class="xCNRptFilter">
                            <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptRackFrom']; ?> : </span> <?php echo $aDataFilter['tRackNameFrom']; ?></label>
                            <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptRackTo']; ?> : </span> <?php echo $aDataFilter['tRackNameTo']; ?></label>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>