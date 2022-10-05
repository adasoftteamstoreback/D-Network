<?php
    $aCompanyInfo = $aDataViewRpt['aCompanyInfo'];
    $aDataFilter = $aDataViewRpt['aDataFilter'];
    $aDataTextRef = $aDataViewRpt['aDataTextRef'];
    $aDataReport = $aDataViewRpt['aDataReport'];
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
        /* border-bottom: 1px dashed #ccc !important; */
        /* border-bottom: dashed 1px #333 !important; */
    }

    .xWRptMSORightline1 {

    border-right: 1px  solid black !important;
    }

    .xWRptMSOleftline1 {

    border-left: 1px  solid black !important;
    }

    .xWRptMSOUnderline {

        border-bottom: 1px solid black !important;
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

    .table>thead{
        border-bottom: 1px solid black !important;
    }

    .xCNBackspace {
        margin-left: 10px;
    }

    /*แนวนอน*/
    /* @media print{@page {size: landscape}} */
    /*แนวตั้ง*/
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
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 report-filter">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center">
                                <label class="xCNRptTitle"><?php echo $aDataTextRef['tTitleReport']; ?></label>
                            </div>
                        </div>
                    </div>

                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateFrom'] ?> : </label>   <label><?=date('d/m/Y',strtotime($aDataFilter['tDocDateFrom'])); ?></label>
                                   <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateTo'] ?> : </label>   <label><?=date('d/m/Y',strtotime($aDataFilter['tDocDateTo'])); ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ((isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom'] ?> : </label>   <label><?=$aDataFilter['tBchNameFrom']; ?></label>
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchTo'] ?> : </label>   <label><?=$aDataFilter['tBchNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php }; ?>

                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    &nbsp;
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
            <div id="odvRptTableRPD" class="table-responsive">
                <table class="table" >
                    <thead>
                        <tr>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerPickBch']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerPickDate']; ?></th>
                            <th class="text-left xCNRptColumnHeader" width="10%"><?php echo $aDataTextRef['tRptLockerPickRefSal']; ?></th>
                            <th class="text-left xCNRptColumnHeader" width="10%"><?php echo $aDataTextRef['tRptLockerPickRefDoc']; ?></th>
                            <th class="text-left xCNRptColumnHeader" width="15%">เลขที่อ้างอิงเอกสารภายนอก</th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerPickPdtCode']; ?></th>
                            <th class="text-left xCNRptColumnHeader" width="10%"><?php echo $aDataTextRef['tRptLockerPickPdtName']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerPickPdtBarCode']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerPickPdtPun']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerPickPdyQ']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerPickLayName']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerPickLayNo']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerPickUser']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerPickReason']; ?></th>
                        
                    </thead>
                    <tbody>
                        <?php if(!empty($aDataReport['aRptData'])){ $tBchCodeAct = "";?>
                            
                            <?php foreach($aDataReport['aRptData'] as $aData) { ?>
                                <?php 
                                    // $tStyleBorder = '';
                                    // $tBchName = '';
                                    // if ($aData['COUNTBYBCH'] == $aData['PARTITIONBYBCH']) {
                                    //     $tStyleBorder = 'border-bottom: 1px solid black !important;';
                                    // }else{
                                    //     $tStyleBorder = '';
                                    // } 
                                    
                                    // if ($aData['PARTITIONBYBCH'] == 1) {
                                    //     $tBchName = $aData['FTBchName'];
                                    // }else{
                                    //     $tBchName = '';
                                    // }
                                    if($tBchCodeAct != $aData['FTBchCode']) {
                                        $tBchCodeAct = $aData['FTBchCode'];
                                ?>
                                <tr style="border-top: solid 1px #333 !important;">                 
                                <td class="xCNRptDetail text-left"><?php echo $aData['FTBchName']?></td>
                                <?php }else{ ?>
                                <td class="xCNRptDetail text-left"> </td>
                                <?php } ?>
                                    <td class="text-left xCNRptDetail"><?=$aData['FDXshDatePick']?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTXshDocNo']?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTXshRefDocNo']?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTXshRefEXDocNo']?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTPdtCode']?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTPdtName']?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTXsdBarCode']?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTPunName']?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aData['FCXsdQty'], $nOptDecimalShow)?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTPosName']?></td>
                                    <td class="text-left xCNRptDetail"><?=(!empty($aData['FTLayName'])?$aData['FTLayName']:'Virtual')?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTXshToLogin']?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTRsnName']?></td>
                                </tr>
                            <?php } ?>
                        <?php }else{ ?>
                            <tr>
                                <td colspan="100%" class="text-center xCNRptColumnFooter"><?php echo $aDataTextRef['tRptNoData']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!--เเสดงหน้า-->
            <div class="xCNRptFilterTitle">
                <div class="text-right">
                    <label><?=language('report/report/report','tRptPage')?> <?=$aDataReport["aPagination"]["nDisplayPage"]?> <?=language('report/report/report','tRptTo')?> <?=$aDataReport["aPagination"]["nTotalPage"]?> </label>
                </div>
            </div>

            <div class="xCNRptFilterTitle">
                <div class="text-left">
                    <label class="xCNTextConsOth"><?=$aDataTextRef['tRptConditionInReport'];?></label>
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
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjMerChantFrom'].' : </span>'.$aDataFilter['tMerNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjMerChantTo'].' : </span>'.$aDataFilter['tMerNameTo'];?></label>
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

            <!-- ============================ ฟิวเตอร์ข้อมูล ร้านค้า ============================ -->
            <?php if ((isset($aDataFilter['tShpCodeFrom']) && !empty($aDataFilter['tShpCodeFrom'])) && (isset($aDataFilter['tShpCodeTo']) && !empty($aDataFilter['tShpCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjShopFrom'].' : </span>'.$aDataFilter['tShpNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjShopTo'].' : </span>'.$aDataFilter['tShpNameTo'];?></label>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($aDataFilter['tShpCodeSelect']) && !empty($aDataFilter['tShpCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptShopFrom']; ?> : </span> <?php echo ($aDataFilter['bShpStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tShpNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ============================ ฟิวเตอร์ข้อมูล ลูกค้า ============================ -->
            <?php if ((isset($aDataFilter['tCstCodeFrom']) && !empty($aDataFilter['tCstCodeFrom'])) && (isset($aDataFilter['tCstCodeTo']) && !empty($aDataFilter['tCstCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom'].' : </span>'.$aDataFilter['tShpNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstTo'].' : </span>'.$aDataFilter['tShpNameTo'];?></label>
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

            <!-- ============================ ฟิวเตอร์ข้อมูล ประเภทการชำระ ============================ -->
            <?php if ((isset($aDataFilter['tRcvCodeFrom']) && !empty($aDataFilter['tRcvCodeFrom'])) && (isset($aDataFilter['tRcvCodeTo']) && !empty($aDataFilter['tRcvCodeTo']))): ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptRcvFrom'].' : </span>'.$aDataFilter['tRcvNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptRcvTo'].' : </span>'. $aDataFilter['tRcvNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
    $(document).ready(function(){
        var oFilterLabel = $('.report-filter .text-left label:first-child');
        var nMaxWidth = 0;
        oFilterLabel.each(function(index){
            var nLabelWidth = $(this).outerWidth();
            if(nLabelWidth > nMaxWidth){
                nMaxWidth = nLabelWidth;
            }
        });
        $('.report-filter .text-left label:first-child').width(nMaxWidth + 50);
    });
</script>
