<?php
    $aDataReport        = $aDataViewRpt['aDataReport'];
    $aDataTextRef       = $aDataViewRpt['aDataTextRef'];
    $aDataFilter        = $aDataViewRpt['aDataFilter'];
    $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
?>
<style>
    .xCNFooterRpt {
        border-bottom   : 7px double #ddd;
    }

    .table thead th, .table>thead>tr>th, .table tbody tr, .table>tbody>tr>td {
        border          : 0px transparent !important;
    }

    .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
        border-top      : 1px solid black !important;
        border-bottom   : 1px solid black !important;
    }

    .table>tbody>tr.xCNTrSubFooter{
        border-top      : 1px solid black !important;
        border-bottom   : 1px solid black !important;
    }

    .table>tbody>tr.xCNTrFooter{
        border-top      : dashed 1px #333 !important;
        border-bottom   : 1px solid black !important;
    }

    /*แนวนอน*/
    @media print{@page {size: landscape;
        margin: 1.5mm 1.5mm 1.5mm 1.5mm;
    }}

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
                                <label class="xCNRptTitle"><?=$aDataTextRef['tTitleReport'];?></label>
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
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateFrom'] ?> : </label> <label><?= date('d/m/Y', strtotime($aDataFilter['tDocDateFrom'])); ?></label>&nbsp;
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateTo'] ?> : </label> <label><?= date('d/m/Y', strtotime($aDataFilter['tDocDateTo'])); ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <label class="xCNRptDataPrint"><?=$aDataTextRef['tDatePrint'].' '.date('d/m/Y').' '.$aDataTextRef['tTimePrint'].' '.date('H:i:s');?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptBarchCode');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRPC15TBBchName');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptLockerPaymentXsdDocDate');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptRPDDocNo');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptCstLostContLicense');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptRPDCode');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:20%;"><?=language('report/report/report','tRptRPDNamePdt');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:6%;"><?=language('report/report/report','tRptInventoriesByBchPrice');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:6%;"><?=language('report/report/report','tRptTnfQty');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:6%;"><?=language('report/report/report','tRptCrTotal');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:6%;"><?=language('report/report/report','tRptDiscount');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:6%;"><?=language('report/report/report','tRptPriceGrand');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptStaVat');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptSaleByBillVAT');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <tr>
                                    <td class="xCNRptDetail"><?=$aValue['FTBchCode']?></td>
                                    <td class="xCNRptDetail"><?=($aValue['FTBchName'] == '') ? '-' : $aValue['FTBchName']?></td>
                                    <td class="xCNRptDetail"><?=date('d/m/Y', strtotime($aValue['FDXshDocDate']));?></td>
                                    <td class="xCNRptDetail" ><?=$aValue['FTXshDocNo']?></td>
                                    <td class="xCNRptDetail" ><?=($aValue['FTCarRegNo'] == '') ? '-' : $aValue['FTCarRegNo']?></td>
                                    <td class="xCNRptDetail" ><?=$aValue['FTPdtCode']?></td>
                                    <td class="xCNRptDetail" ><?=$aValue['FTXsdPdtName']?></td>
                                    <td class="xCNRptDetail text-right" ><?=number_format($aValue['FCXsdSalePrice'],2)?></td>
                                    <td class="xCNRptDetail text-right" ><?=number_format($aValue['FCXsdQtyAll'],0)?></td>
                                    <td class="xCNRptDetail text-right" ><?=number_format($aValue['FCXsdAmtB4DisChg'],2)?></td>
                                    <td class="xCNRptDetail text-right" ><?=number_format($aValue['FCXsdDis'],2)?></td>
                                    <td class="xCNRptDetail text-right" ><?=number_format($aValue['FCXsdNetAfHD'],2)?></td>
                                    <td class="xCNRptDetail" >
                                        <?php if($aValue['FTCbaStaTax'] == 1){ ?>
                                            <?php echo language('report/report/report','tRptStaTax1'); ?>
                                        <?php }else if($aValue['FTCbaStaTax'] == 2){ ?>
                                            <?php echo language('report/report/report','tRptStaTax2'); ?>
                                        <?php }else{ ?>
                                            ไม่ระบุ
                                        <?php } ?>
                                    </td>
                                    <td class="xCNRptDetail text-right" ><?=number_format($aValue['FCXsdVat'], 2)?></td>
                                </tr>
                            <?php } ?>
                            <?php //ผลรวมถ้าเป็นหน้าสุดท้าย
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                $aDataTotal = $aDataReport['aRptData'][0];

                                if ($nPageNo == $nTotalPage) { ?>
                                <tr class='xCNTrFooter'>
                                    <td class='xCNRptSumFooter text-left' colspan='8'><?=language('report/report/report', 'tRptTotalFooter')?></td>
                                    <td class='xCNRptSumFooter text-right'><?=number_format($aDataTotal['FCXsdQtyAll_Total'], 0)?></td>
                                    <td class='xCNRptSumFooter text-right'><?=number_format($aDataTotal['FCXsdAmtB4DisChg_Total'], 2)?></td>
                                    <td class='xCNRptSumFooter text-right'><?=number_format($aDataTotal['FCXsdDis_Total'], 2)?></td>
                                    <td class='xCNRptSumFooter text-right'><?=number_format($aDataTotal['FCXsdNetAfHD_Total'], 2)?></td>
                                <tr>
                            <?php } ?>
                        <?php }else { ?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?=$aDataTextRef['tRptNoData'];?></td></tr>
                        <?php } ;?>
                    </tbody>
                </table>
            </div>

            <div class="xCNRptFilterTitle">
                <div class="text-left">
                    <label class="xCNTextConsOth"><?=$aDataTextRef['tRptConditionInReport'];?></label>
                </div>
            </div>

            <?php if (isset($aDataFilter['tBchCodeSelect']) && !empty($aDataFilter['tBchCodeSelect'])) : ?>
                <!-- ===== ฟิวเตอร์ข้อมูล สาขา =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['bBchStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tBchNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
