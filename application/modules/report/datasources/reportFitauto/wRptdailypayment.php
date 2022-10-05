<?php
    $aDataReport    = $aDataViewRpt['aDataReport'];
    $aDataTextRef   = $aDataViewRpt['aDataTextRef'];
    $aDataFilter    = $aDataViewRpt['aDataFilter'];
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
                            <th nowrap class="text-left xCNRptColumnHeader" style="vertical-align : middle;text-align:left; width:10%;"><?php echo language('report/report/report','tRptdailypaymentCol1');?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="vertical-align : middle;text-align:center; width:60%;"><?php echo language('report/report/report','tRptdailypaymentCol2');?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="vertical-align : middle;text-align:right; width:10%;"><?php echo language('report/report/report','tRptdailypaymentCol3');?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="vertical-align : middle;text-align:right; width:10%;"><?php echo language('report/report/report','tRptdailypaymentCol4');?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="vertical-align : middle;text-align:right; width:10%;"><?php echo language('report/report/report','tRptdailypaymentCol5');?></th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php $tKeepSPL     = ''; ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <?php 
                                    //รวมคอลัมน์
                                    if($aValue['rtPartitionSPL'] == 1 || $aValue['rtPartitionSPL'] == 0){
                                        $nRowspan = '';
                                    }else{
                                        $nRowspan = "rowspan=".$aValue['rtPartitionSPL'];
                                    }
                                ?>
                                <tr>    
                                    <?php if($aValue['rtPartitionSPL'] == 1){ ?>
                                        <td class="text-left xCNRptDetail" ><?=date("d/m/Y", strtotime($aValue['FDXphLastPay']));?></td>
                                    <?php }else{ ?>
                                        <?php if($tKeepSPL != $aValue['FTSplCode'] ) { ?>
                                            <td <?=$nRowspan?> class="text-left xCNRptDetail" ><?=date("d/m/Y", strtotime($aValue['FDXphLastPay']));?></td>
                                        <?php } ?>
                                    <?php } ?>
                                    <td class="text-left xCNRptDetail">(<?=$aValue['FTSplCode']; ?>) <?=$aValue['FTSplName']; ?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphGrand'],$nOptDecimalShow ) ?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphPaid'],$nOptDecimalShow ) ?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphLeft'],$nOptDecimalShow ) ?></td>
                                </tr>

                                <?php if( $aValue['rtPartitionDate'] == $aValue['rtPartitionDateNumber'] ){ ?>
                                    <tr style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='xCNTrFooter'>
                                        <td class="text-left xCNRptGrouPing"><?=$aDataTextRef['tRptAdjStkVDTotalSub']; ?>วันที่ <?=date("d/m/Y", strtotime($aValue['FDXphLastPay']));?></td>
                                        <td class="text-left xCNRptGrouPing"></td>
                                        <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphGrand_Date_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphPaid_Date_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptDetail"><?=number_format($aValue['FCXphLeft_Date_Footer'],$nOptDecimalShow ) ?></td>
                                    </tr>
                                <?php } ?>
                                 
                                <?php $tKeepSPL     = $aValue['FTSplCode']; ?>
                            <?php } ?>
                            <?php
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                if ($nPageNo == $nTotalPage) { ?>
                                    <tr class='xCNTrFooter'>
                                        <td class="text-left xCNRptGrouPing"><?=$aDataTextRef['tRptAdjStkVDTotalSub']; ?></td>
                                        <td class="text-left xCNRptGrouPing"></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphGrand_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphPaid_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXphLeft_Footer'],$nOptDecimalShow ) ?></td>
                                    </tr>
                            <?php } ?>
                        <?php }else{ ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?=$aDataTextRef['tRptAdjStkNoData']; ?></td>
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

            <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
            <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptPaymentDate']; ?> : </span> <?php echo date("d/m/Y", strtotime($aDataFilter['tDocDateFrom'])); ?></label>
                        <label><span class="xCNRptFilterHead">ถึง<?php echo $aDataTextRef['tRptPaymentDate']; ?> : </span> <?php echo date("d/m/Y", strtotime($aDataFilter['tDocDateTo'])); ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ============================ ฟิวเตอร์ข้อมูล ผู้จำหน่าย ============================ -->
            <?php if ((isset($aDataFilter['tPdtSupplierCodeFrom']) && !empty($aDataFilter['tPdtSupplierCodeFrom'])) && (isset($aDataFilter['tPdtSupplierCodeTo']) && !empty($aDataFilter['tPdtSupplierCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplFrom']; ?> : </span> <?php echo $aDataFilter['tPdtSupplierNameFrom']; ?></label>
                        <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTo']; ?> : </span> <?php echo $aDataFilter['tPdtSupplierNameTo']; ?></label>
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
