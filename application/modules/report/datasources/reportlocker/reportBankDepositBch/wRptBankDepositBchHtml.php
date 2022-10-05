<?php
    $aCompanyInfo       = $aDataViewRpt['aCompanyInfo'];
    $aDataFilter        = $aDataViewRpt['aDataFilter'];
    $aDataTextRef       = $aDataViewRpt['aDataTextRef'];
    $aDataReport        = $aDataViewRpt['aDataReport'];
    $nOptDecimalShow    = $aDataViewRpt['nOptDecimalShow'];
?>
<style>
    .xCNFooterRpt {
        border-bottom : 7px double #ddd;
    }

    .table thead th, .table>thead>tr>th, .table tbody tr, .table>tbody>tr>td {
        border: 0px transparent !important;
    }

    .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
        border-top: 1px solid black !important;
        border-bottom: dashed 1px #333 !important;
    }

    .table tbody tr.xCNRptSumFooterTrTop,
    .table>tbody>tr.xCNRptSumFooterTrTop>td {
        border: 0px solid black !important;
        border-top: 1px solid black !important;
    }

    .table tbody tr.xCNRptLastGroupTr,
    .table>tbody>tr.xCNRptLastGroupTr>td {
        border: 0px solid black !important;
        border-bottom: 1px dashed #ccc !important;
    }

    .table tbody tr.xCNRptSumFooterTrBottom,
    .table>tbody>tr.xCNRptSumFooterTrBottom>td {
        border: 0px solid black !important;
        border-bottom: 1px solid black !important;
    }

    .xWRptMovePosVDData>td:first-child{
        text-indent: 40px;
    }

    /*แนวนอน*/
    @media print{@page {
        size: A4 landscape;
        margin: 5mm 5mm 5mm 5mm;
    }}
</style>
<div id="odvRptMovePosVDHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">

            <div class="row">
                <?php include 'application\modules\report\datasources\Address\wRptAddress.php'; ?>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center">
                                <label class="xCNRptTitle"><?php echo @$aDataTextRef['tTitleReport'];?></label>
                            </div>
                        </div>
                    </div>

                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) : ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?= $aDataTextRef['tRptDateFrom'] ?> : </label> <label><?= date('d/m/Y', strtotime($aDataFilter['tDocDateFrom'])); ?> </label>&nbsp;
                                    <label class="xCNRptFilterHead"><?= $aDataTextRef['tRptDateTo'] ?> : </label> <label><?= date('d/m/Y', strtotime($aDataFilter['tDocDateTo'])); ?> </label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 table-responsive">
                    <div class="text-right">
                        <label class="xCNRptDataPrint"><?php echo $aDataTextRef['tDatePrint'].' '.date('d/m/Y').' '.$aDataTextRef['tTimePrint'].' '.date('H:i:s');?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr style="border-bottom: 1px solid black !important">
                            <th nowrap class="text-left xCNRptColumnHeader" ><?=language('report/report/report','สาขา');?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" ><?=language('report/report/report','tRptDateDocument');?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" ><?=language('report/report/report','tRptBnkdplDocno');?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" ><?=language('report/report/report','tRptBnkdplBnkAccno');?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" ><?=language('report/report/report','tRptBnkdplBnkAccType');?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" ><?=language('report/report/report','tRptBnkdplBnkBddType');?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" ><?=language('report/report/report','tRptBnkdplBnkExtDate');?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" ><?=language('report/report/report','tRptBnkdplRefAmt');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(!empty($aDataReport['aRptData'])){
                            $nTotalarray    = FCNnHSizeOf($aDataReport['aRptData']);
                            $aDataBdd       = array();
                                foreach($aDataReport['aRptData'] as $k => $aValue){
                                    $sum1     = $aValue['Subtype1'];
                                    $sum2     = $aValue['Subtype2'];
                                    $cXrcVatable_Footer = $aValue['FCXrcNet_Footer'];
                                ?>

                                <tr>
                                    <td class="text-left xCNRptDetail"><?=$aValue['FTBchName'];?></td>
                                    <td class="text-left xCNRptDetail "><?=date('d/m/Y',strtotime($aValue['FDBdhDate'])); ?></td>
                                    <td class="text-left xCNRptDetail"><?=$aValue['FTBdhDocNo'];?></td>
                                    <td class="text-left xCNRptDetail"><?=$aValue['FTBbkAccNo'];?></td>
                                    <td class="text-left xCNRptDetail" ><?=$aValue['FTBbkType'];?></td>
                                    <td class="text-left xCNRptDetail"><?=$aValue['FTBddType'];?></td>
                                    <td class="text-left xCNRptDetail "><?=date('d/m/Y',strtotime($aValue['FDBdhRefExtDate'])); ?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue["FCBddRefAmt"], $nOptDecimalShow);?></td>
                                </tr>

                                <!--รวมยอดตามสาขา-->
                                <?php if($aValue['PARTTITIONBYBCH'] == $aValue['PARTTITIONBYBCH_COUNT']){ ?>
                                    <tr class="xCNRptLastGroupTr">
                                        <td class="xCNRptSumFooter" colspan="7">รวม <?=$aValue['FTBchName']; ?></td>
                                        <td class="text-right xCNRptSumFooter"><?=number_format($aValue["FCXrcNet_SubByBch"], $nOptDecimalShow);?></td>
                                    </tr>
                                <?php } ?>
                        <?php } ?>
                        <?php
                            $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                            if ($nPageNo == $nTotalPage) { ?>
                                <tr class="xCNRptSumFooterTrTop">
                                    <td class="text-left xCNRptSumFooter"><?php echo $aDataTextRef['tRptTotalFooter']; ?></td>
                                    <td colspan="6"></td>
                                    <td class="text-right xCNRptSumFooter"><?php echo number_format($aValue['FCXrcNet_Footer'],2);?></td>
                                </tr>
                                <tr class="xCNRptSumFooterTrBottom"></tr>
                            <?php } ?>
                        <?php }else{ ?>
                            <tr>
                                <td colspan="17" class="text-center xCNRptColumnFooter" ><?php echo $aDataTextRef['tRptNoData']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <?php if ((isset($aDataFilter['tPdtCodeFrom']) && !empty($aDataFilter['tPdtCodeFrom'])) && (isset($aDataFilter['tPdtCodeTo']) && !empty($aDataFilter['tPdtCodeTo']))
                    || (isset($aDataFilter['tWahCodeFrom']) && !empty($aDataFilter['tWahCodeFrom'])) && (isset($aDataFilter['tWahCodeTo']) && !empty($aDataFilter['tWahCodeTo']))
                    || (isset($aDataFilter['tMerCodeFrom']) && !empty($aDataFilter['tMerCodeFrom'])) && (isset($aDataFilter['tMerCodeTo']) && !empty($aDataFilter['tMerCodeTo']))
                    || (isset($aDataFilter['tShpCodeFrom']) && !empty($aDataFilter['tShpCodeFrom'])) && (isset($aDataFilter['tShpCodeTo']) && !empty($aDataFilter['tShpCodeTo']))
                    || (isset($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeFrom'])) && (isset($aDataFilter['tPosCodeTo']) && !empty($aDataFilter['tPosCodeTo']))
                    || (isset($aDataFilter['tBchCodeSelect']))
                    || (isset($aDataFilter['tMerCodeSelect']))
                    || (isset($aDataFilter['tShpCodeSelect']))
                    || (isset($aDataFilter['tPosCodeSelect']))
                    || (isset($aDataFilter['tWahCodeSelect']))
                    ) { ?>
                <div class="xCNRptFilterTitle">
                    <div class="text-left">
                        <label class="xCNTextConsOth"><?=$aDataTextRef['tRptConditionInReport'];?></label>
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

             <!-- ============================ ฟิวเตอร์ข้อมูล เลขบช ============================ -->
             <?php if ((isset($aDataFilter['tAccNoFrom']) && !empty($aDataFilter['tAccNoFrom'])) && (isset($aDataFilter['tAccNoTo']) && !empty($aDataFilter['tAccNoTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBnkdplBnkAccFrom'].' : </span>'.$aDataFilter['tAccNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBnkdplBnkAccTo'].' : </span>'.$aDataFilter['tAccNameTo'];?></label>
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
