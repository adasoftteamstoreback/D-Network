<?php
    $aDataReport = $aDataViewRpt['aDataReport'];
    $aDataTextRef = $aDataViewRpt['aDataTextRef'];
    $aDataFilter = $aDataViewRpt['aDataFilter'];
    $nOptDecimalShow = FCNxHGetOptionDecimalShow();
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
    }

    .table>thead:first-child>tr:last-child>td,
    .table>thead:first-child>tr:last-child>th {
        border-bottom: 1px solid black !important;
    }
    .xWRptProductFillData>td:first-child {
        text-indent: 40px;
    }
    /*แนวนอน*/
    /* @media print{@page {size: landscape}} */
    /*แนวตั้ง*/

    @media print{@page {size: landscape;
        margin: 1.5mm 1.5mm 1.5mm 1.5mm;
    }}
</style>
<div id="odvRptAdjPriceHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <div class="row">
                <?php include 'application\modules\report\datasources\Address\wRptAddress.php'; ?>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 report-filter">

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center">
                                <label class="xCNRptTitle"><?php echo $aDataTextRef['tTitleReport'];?></label>
                            </div>
                        </div>
                    </div>

                    <?php if((isset($aDataFilter['tRptDocDateFrom']) && !empty($aDataFilter['tRptDocDateFrom'])) && (isset($aDataFilter['tRptDocDateTo']) && !empty($aDataFilter['tRptDocDateTo']))): ?>
                        <!-- ===== ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ================= ========= -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateFrom']?></label>   <label><?=date('d/m/Y',strtotime($aDataFilter['tRptDocDateFrom']));?>  </label>&nbsp;
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateTo']?></label>     <label><?=date('d/m/Y',strtotime($aDataFilter['tRptDocDateTo']));?>    </label>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>

                    <?php if( (isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                        <!-- ===== ฟิวเตอร์ข้อมูล สาขา =================================== -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptTaxSalePosFilterBchFrom']?></label> <label><?=$aDataFilter['tBchNameFrom'];?></label>&nbsp;
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptTaxSalePosFilterBchTo']?></label> <label><?=$aDataFilter['tBchNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ;?>

                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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

                        <tr>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRptSalByPdtSetPdtCode'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:15%;"><?php echo language('report/report/report', 'tRptSalByPdtSetPdtName'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRptSalByPdtSetQty'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%;"><?php echo language('report/report/report', 'tRptSalByPdtSetUnit'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:5%;"><?php echo language('report/report/report', 'tRptValue'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:5%;"><?php echo language('report/report/report', 'tRptDiscount'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:5%;"><?php echo language('report/report/report', 'tRptVat'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRptEndDayTotal'); ?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                              $nFCXpdValueSum = 0;
                              $nFCXpdDisSum = 0;
                              $nFCXpdVatSum = 0;
                              $nFCXpdNetAmtSum = 0;
                         ?>
                        <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) {  ?>
                                <tr>
                                    <td  class="text-left xCNRptDetail"><?php echo $aValue['FTPdtCode']; ?></td>
                                    <td  class="text-left xCNRptDetail"><?php echo $aValue['FTPdtName']; ?></td>
                                    <td  class="text-right xCNRptDetail"><?php echo number_format($aValue['FCXpdQty'],$nOptDecimalShow); ?></td>
                                    <td  class="text-left xCNRptDetail"><?php echo $aValue['FTPunName']; ?></td>
                                    <td  class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXpdValue"],$nOptDecimalShow); ?></td>
                                    <td  class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXpdDis"],$nOptDecimalShow); ?></td>
                                    <td  class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXpdVat"],$nOptDecimalShow); ?></td>
                                    <td  class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXpdNetAmt"],$nOptDecimalShow); ?></td>
                                </tr>
                                
                            <?php } ?>
                        <?php } ?>
                        <?php

                            //Summary Footer
                            $aDataTotal = $aDataReport['aRptData'][0];
                            $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];

                            if ($nPageNo == $nTotalPage) { ?>
                                <tr class='xCNTrFooter'>
                                    <td  class="text-left xCNRptGrouPing"><?php echo language('report/report/report', 'tRptTotalSub'); ?></td>
                                    <td  class="text-left xCNRptGrouPing"></td>
                                    <td  class="text-right xCNRptGrouPing"></td>
                                    <td  class="text-left xCNRptGrouPing"></td>
                                    <td  class="text-right xCNRptGrouPing"><?php echo number_format($aDataTotal["FCXpdValue_Footer"],$nOptDecimalShow); ?></td>
                                    <td  class="text-right xCNRptGrouPing"><?php echo number_format($aDataTotal["FCXpdDis_Footer"],$nOptDecimalShow); ?></td>
                                    <td  class="text-right xCNRptGrouPing"><?php echo number_format($aDataTotal["FCXpdVat_Footer"],$nOptDecimalShow); ?></td>
                                    <td  class="text-right xCNRptGrouPing"><?php echo number_format($aDataTotal["FCXpdNetAmt_Footer"],$nOptDecimalShow); ?></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td class='text-center xCNTextDetail2' colspan='100%'><?php echo $aDataTextRef['tRptAdjStkNoData']; ?></td>
                                </tr>
                            <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="xCNRptFilterTitle"> <!-- style="margin-top: 10px;" -->
                <div class="text-left">
                    <label class="xCNTextConsOth"><?=$aDataTextRef['tRptConditionInReport'];?></label>
                </div>
            </div>

            <?php if (isset($aDataFilter['tBchCodeSelect']) && !empty($aDataFilter['tBchCodeSelect'])) : ?>
                <!-- ===== ฟิวเตอร์ข้อมูล สาขา =========================================== -->
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['bBchStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tBchNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <?php if((isset($aDataFilter['tPdtDateFrom']) && !empty($aDataFilter['tPdtDateFrom']))): ?>
                <!-- ===== ฟิวเตอร์ข้อมูล วันที่เอกสาร ========================== -->
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="text-left">
                            <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateFrom']?></label>   <label><?=date('d/m/Y',strtotime($aDataFilter['tPdtDateFrom']));?>  </label>&nbsp;
                        </div>
                    </div>
                </div>
            <?php endif;?>

            <!-- ===== ฟิวเตอร์ข้อมูล สถานะเคลื่อนไหว ========================== -->
            <?php 
                if ($aDataFilter['tPdtStaActive'] == '1') {
                    $tRptPdtStaActive = $aDataTextRef['tRptPdtMoving1'];
                }elseif ($aDataFilter['tPdtStaActive'] == '2'){
                    $tRptPdtStaActive = $aDataTextRef['tRptPdtMoving2'];
                }else{
                    $tRptPdtStaActive = $aDataTextRef['tRptAll'];
                }
            ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-left">
                        <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptTitlePdtMoving'].' : '?></label>   <label><?=$tRptPdtStaActive;?>  </label>&nbsp;
                    </div>
                </div>
            </div>

            <?php if( (isset($aDataFilter['tPdtSupplierCodeFrom']) && !empty($aDataFilter['tPdtSupplierCodeFrom'])) && (isset($aDataFilter['tPdtSupplierCodeTo']) && !empty($aDataFilter['tPdtSupplierCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ผู้จำหน่าย =========================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplFrom'].' : </span>'.$aDataFilter['tPdtSupplierNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTo'].' : </span>'.$aDataFilter['tPdtSupplierNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tPdtCodeFrom']) && !empty($aDataFilter['tPdtCodeFrom'])) && (isset($aDataFilter['tPdtCodeTo']) && !empty($aDataFilter['tPdtCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล สินค้า =========================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeFrom'].' : </span>'.$aDataFilter['tPdtNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeTo'].' : </span>'.$aDataFilter['tPdtNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tPdtTypeCodeFrom']) && !empty($aDataFilter['tPdtTypeCodeFrom'])) && (isset($aDataFilter['tPdtTypeCodeTo']) && !empty($aDataFilter['tPdtTypeCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ประเภทสินค้า ======================================= -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtTypeFrom'].' : </span>'.$aDataFilter['tPdtTypeNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtTypeTo'].' : </span>'.$aDataFilter['tPdtTypeNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tPdtGrpCodeFrom']) && !empty($aDataFilter['tPdtGrpCodeFrom'])) && (isset($aDataFilter['tPdtGrpCodeTo']) && !empty($aDataFilter['tPdtGrpCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล กลุ่มสินค้า =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtGrpFrom'].' : </span>'.$aDataFilter['tPdtGrpNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtGrpTo'].' : </span>'.$aDataFilter['tPdtGrpNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tPdtBrandCodeFrom']) && !empty($aDataFilter['tPdtBrandCodeFrom'])) && (isset($aDataFilter['tPdtBrandCodeTo']) && !empty($aDataFilter['tPdtBrandCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ยี่ห้อ =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBrandFrom'].' : </span>'.$aDataFilter['tPdtBrandNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBrandTo'].' : </span>'.$aDataFilter['tPdtBrandNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tPdtModelCodeFrom']) && !empty($aDataFilter['tPdtModelCodeFrom'])) && (isset($aDataFilter['tPdtModelCodeTo']) && !empty($aDataFilter['tPdtModelCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล รุ่น =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptModelFrom'].' : </span>'.$aDataFilter['tPdtModelNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptModelTo'].' : </span>'.$aDataFilter['tPdtModelNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

        </div>
        <div class="xCNFooterPageRpt">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <?php if($aDataReport["aPagination"]["nTotalPage"] > 0):?>
                            <label class="xCNRptLabel"><?php echo $aDataReport["aPagination"]["nDisplayPage"].' / '.$aDataReport["aPagination"]["nTotalPage"]; ?></label>
                        <?php endif;?>
                    </div>
                </div>
            </div>
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
