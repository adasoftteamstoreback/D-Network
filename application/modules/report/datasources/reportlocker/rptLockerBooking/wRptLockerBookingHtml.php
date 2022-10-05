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
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerBookingBch']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerBookingDate']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerBookingDocNo']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptDateDocument')?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRPC1TBCardTxnDocNoRef')?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptSaleQuantationRefDocIntDate')?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'เอกสารอ้างอิงภายนอก')?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerBookingPdtCode']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerBookingPdtName']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerBookingPdtBarCode']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerBookingPdtPun']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerBookingPdyQ']; ?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptLockerBookingSopName');?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptLockerBookingPosName');?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptLockerBookingLayName');?></th>
                            <th class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptLockerBookingCst']; ?></th>
                        
                    </thead>
                    <tbody>
                        <?php if(!empty($aDataReport['aRptData'])){ $tBchCodeAct = "";?>
                            
                            <?php foreach($aDataReport['aRptData'] as $aData) { ?>
                                <?php 
                                    if ($aData['COUNTBYBCH'] == $aData['PARTITIONBYBCH']) {
                                        $tStyle = 'border-bottom: solid 1px #333 !important;';
                                    }else{
                                        $tStyle = '';
                                    }

                                    if ($aData['PARTITIONBYBCH'] == 1 || $aData['PARTITIONBYBCH2'] == 1) {
                                        $tBchName = $aData['FTBchName'];
                                    }else {
                                        $tBchName = '';
                                    }
                                    
                                    if ($aData['PARTITIONBYDOC'] == 1 || $aData['PARTITIONBYDOC2'] == 1) {
                                        $tBookDate = date('Y-m-d H:i:s', strtotime($aData['FDXshBookDate']));
                                        $tDocNo = $aData['FTXshDocNo'];
                                        $tDocDate = date('d/m/Y', strtotime($aData['FDCreateOn']));
                                    }else{
                                        $tBookDate = '';
                                        $tDocNo = '';
                                        $tDocDate = '';
                                    }

                                    if ($aData['PARTITIONBYREFDOC'] == 1) {
                                        $tRefDocNo = $aData['FTXshRefDocNo'];
                                        $tRefDocNoEX = $aData['FTXshRefEXDocNo'];
                                        $tRefDocDate = date('d/m/Y', strtotime($aData['FDXshRefDocDate']));
                                    }elseif ($aData['PARTITIONBYREFDOC2'] == 1) {
                                        $tRefDocNo = $aData['FTXshRefDocNo'];
                                        $tRefDocNoEX = $aData['FTXshRefEXDocNo'];
                                        $tRefDocDate = date('d/m/Y', strtotime($aData['FDXshRefDocDate']));
                                    }else{
                                        $tRefDocNo = '';
                                        $tRefDocDate = '';
                                        $tRefDocNoEX = '';
                                    }
                                ?>
                                <tr style="<?=$tStyle?>">                 
                                    <td class="xCNRptDetail text-left"><?php echo $tBchName?></td>
                                    <td class="text-left xCNRptDetail"><?=$tBookDate?></td>
                                    <td class="text-left xCNRptDetail"><?=$tDocNo?></td>
                                    <td class="text-left xCNRptDetail"><?=$tDocDate?></td>
                                    <td class="text-left xCNRptDetail"><?=$tRefDocNo?></td>
                                    <td class="text-left xCNRptDetail"><?=$tRefDocDate?></td>
                                    <td class="text-left xCNRptDetail"><?=$tRefDocNoEX?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTPdtCode']?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTPdtName']?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTXsdBarCode']?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTPunName']?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aData['FCXsdQty'], $nOptDecimalShow)?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTShpName']?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTPosName']?></td>
                                    <td class="text-left xCNRptDetail"><?=!empty($aData['FTLayName']) ? $aData['FTLayName'] : "-"?></td>
                                    <td class="text-left xCNRptDetail"><?=$aData['FTCstName']?></td>
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
