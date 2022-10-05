<?php
    $aDataReport = $aDataViewRpt['aDataReport'];
    $aDataTextRef = $aDataViewRpt['aDataTextRef'];
    $aDataFilter = $aDataViewRpt['aDataFilter'];
    $nOptDecimalShow = FCNxHGetOptionDecimalShow();
?>
<style>
    .xCNFooterRpt {
        border-bottom : 7px double #ddd;
    }

    .table thead th, .table>thead>tr>th, .table tbody tr, .table>tbody>tr>td {
        border: 0px transparent !important;
    }

    .table>thead:first-child>tr:nth-child(1)>td, .table>thead:first-child>tr:nth-child(1)>th {
        border-top: 1px solid black !important;
        border-bottom : 1px solid black !important;

        /* background-color: #CFE2F3 !important; */
    }

    .table>thead:first-child>tr:nth-child(2)>td, .table>thead:first-child>tr:nth-child(2)>th {
        border-bottom : 1px solid black !important;

        /* background-color: #CFE2F3 !important; */
    }

    .table>tbody>tr.xCNTrSubFooter{
        border-top: 1px solid black !important;
        border-bottom : 1px solid black !important;
        /* background-color: #CFE2F3 !important; */
    }

    .table>tbody>tr.xCNTrFooter{
        /* background-color: #CFE2F3 !important; */
        /* border-bottom : 6px double black !important; */
        /* border-top: dashed 1px #333 !important; */
        border-top: 1px solid black !important;
        border-bottom: 1px solid black !important;
    }
    .table tbody tr.xCNHeaderGroup, .table>tbody>tr.xCNHeaderGroup>td {
        /* color: #232C3D !important; */
        font-size: 18px !important;
        font-weight: 600;
    }
    .table>tbody>tr.xCNHeaderGroup>td:nth-child(4), .table>tbody>tr.xCNHeaderGroup>td:nth-child(5) {
        text-align: right;
    }
    /*แนวนอน*/
    @media print{@page {
        size: A4 landscape;
        }
    }
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
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:30%;border-bottom:1px dashed #333 !important;" colspan="5"><?php echo language('report/report/report', 'ลูกค้า');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:30%;border-bottom:1px dashed #333 !important;border-left:1px dashed #333 !important;" colspan="3"><?php echo language('report/report/report', 'tRptCreditCol2');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:30%;border-bottom:1px dashed #333 !important;border-left:1px dashed #333 !important;" colspan="7"><?php echo language('report/report/report', 'tRptCreditCol3');?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:5%;border-left:1px dashed #333 !important;vertical-align: middle;" rowspan="2" ><?php echo language('report/report/report', 'tRptCreditCol4');?></th>
                        </tr>
                        <tr>
                            <th nowrap class="text-left xCNRptColumnHeader" style="border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol5');?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol6');?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol7');?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol8');?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:5%; border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol9');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%; border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol10');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%; border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol11');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%; border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol12');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%; border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol13');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%; border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol14');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%; border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol15');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%; border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol16');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%; border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol17');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%; border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol18');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%; border-right: 1px dashed #333 !important;"><?php echo language('report/report/report', 'tRptCreditCol19');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <tr>
                                    <?php if($aValue['rtPartitionCST'] == 1){ ?>
                                        <tr >
                                            <td class="text-left xCNRptGrouPing" colspan="3">(<?=$aValue['FTCstCode']?>) <?=$aValue['FTCstName']?></td>
                                            <td class="text-right xCNRptGrouPing" colspan="2">วงเงิน <?=number_format($aValue['FCCstCrLimit'])?></td>
                                        </tr>
                                    <?php } ?>

                                    <td  class="text-left xCNRptDetail"><?=date("d/m/Y", strtotime($aValue['FDXshDueDate']))?></td>
                                    <td  class="text-left xCNRptDetail"><?=$aValue['FTXshDocNo']?></td>
                                    <td  class="text-left xCNRptDetail"><?=($aValue['FTXshRefInt'] == '') ? '-' : $aValue['FTXshRefInt']?></td>
                                    <td  class="text-left xCNRptDetail"><?=date("d/m/Y", strtotime($aValue['FDXshDocDate']))?></td>
                                    <td  class="text-right xCNRptDetail"><?=number_format($aValue["FNCstCrTerm"], $nOptDecimalShow)?></td>
                                    <td  class="text-right xCNRptDetail"><?=number_format($aValue['FDXshBFDue60U'], $nOptDecimalShow)?></td>
                                    <td  class="text-right xCNRptDetail"><?=number_format($aValue['FDXshBFDue31T60'], $nOptDecimalShow)?></td>
                                    <td  class="text-right xCNRptDetail"><?=number_format($aValue['FDXshBFDue0T30'], $nOptDecimalShow)?></td>
                                    <td  class="text-right xCNRptDetail"><?=number_format($aValue['FDXshPastDue1'], $nOptDecimalShow)?></td>
                                    <td  class="text-right xCNRptDetail"><?=number_format($aValue['FDXshPastDue2T7'], $nOptDecimalShow)?></td>
                                    <td  class="text-right xCNRptDetail"><?=number_format($aValue['FDXshPastDue8T15'], $nOptDecimalShow)?></td>
                                    <td  class="text-right xCNRptDetail"><?=number_format($aValue['FDXshPastDue16T30'], $nOptDecimalShow)?></td>
                                    <td  class="text-right xCNRptDetail"><?=number_format($aValue['FDXshPastDue31T60'], $nOptDecimalShow)?></td>
                                    <td  class="text-right xCNRptDetail"><?=number_format($aValue['FDXshPastDue61T90'], $nOptDecimalShow)?></td>
                                    <td  class="text-right xCNRptDetail"><?=number_format($aValue['FDXshPastDue90U'], $nOptDecimalShow)?></td>
                                    <td  class="text-right xCNRptDetail"><?=number_format($aValue['FCXshLeft'], $nOptDecimalShow)?></td>

                                    <?php if($aValue['rtPartitionCST'] == $aValue['rtPartitionCountCST']){ ?>
                                        <tr style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='xCNTrFooter'>
                                            <td class="text-left xCNRptGrouPing" colspan="5">รวม (<?=$aValue['FTCstCode']?>) <?=$aValue['FTCstName']?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshBFDue60U_CST_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshBFDue31T60_CST_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshBFDue0T30_CST_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue1_CST_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue2T7_CST_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue8T15_CST_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue16T30_CST_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue31T60_CST_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue61T90_CST_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue90U_CST_Footer'],$nOptDecimalShow ) ?></td>
                                            <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXshLeft_CST_Footer'],$nOptDecimalShow ) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tr>
                            <?php } ?>

                            <?php
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                if ($nPageNo == $nTotalPage) { ?>
                                    <tr style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='xCNTrFooter'>
                                        <td class="text-left xCNRptGrouPing" colspan="5">รวม</td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshBFDue60U_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshBFDue31T60_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshBFDue0T30_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue1_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue2T7_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue8T15_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue16T30_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue31T60_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue61T90_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FDXshPastDue90U_Footer'],$nOptDecimalShow ) ?></td>
                                        <td class="text-right xCNRptGrouPing"><?=number_format($aValue['FCXshLeft_Footer'],$nOptDecimalShow ) ?></td>
                                    </tr>
                            <?php } ?>
                            
                        <?php }else { ?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo $aDataTextRef['tRptAdjStkNoData']; ?></td></tr>
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
                <!-- ===== ฟิวเตอร์ข้อมูล สาขา =========================================== -->
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['bBchStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tBchNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <?php if((isset($aDataFilter['tDueDateFrm']) && !empty($aDataFilter['tDueDateFrm'])) && (isset($aDataFilter['tDueDateTo']) && !empty($aDataFilter['tDueDateTo']))): ?>
                <!-- ===== ฟิวเตอร์ข้อมูล วันที่มีผล ========================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptEffectiveDateFrom'] . ' : </span>' . date('d/m/Y',strtotime($aDataFilter['tDueDateFrm'])); ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptEffectiveDateTo'] . ' : </span>' . date('d/m/Y',strtotime($aDataFilter['tDueDateTo'])); ?></label>
                    </div>
                </div>
            <?php endif;?>

            <?php if((isset($aDataFilter['tCstCodeTo']) && !empty($aDataFilter['tCstCodeTo'])) && (isset($aDataFilter['tCstCodeFrom']) && !empty($aDataFilter['tCstCodeFrom']))): ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ลูกค้า ========================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom'] . ' : </span>' . $aDataFilter['tCstCodeFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstTo'] . ' : </span>' . $aDataFilter['tCstCodeTo']; ?></label>
                    </div>
                </div>
            <?php endif;?>

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
