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
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report','tbypdthisCol1');?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:15%;"><?php echo language('report/report/report','tbypdthisCol2');?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report','tbypdthisCol3');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%;"><?php echo language('report/report/report','tbypdthisCol4');?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:5%;"><?php echo language('report/report/report','tbypdthisCol5');?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:5%;"><?php echo language('report/report/report','tbypdthisCol6');?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:5%;"><?php echo language('report/report/report','tbypdthisCol7');?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report','tbypdthisCol8');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $aData = array();  ?>
                        <?php $aSplName = array();  ?>
                        <?php $nFCXpdValueSum = 0;
                              $nFCXpdDisSum = 0;
                              $nFCXpdVatSum = 0;
                              $nFCXpdNetAmtSum = 0;
                              $nFCXpdQtySum = 0;
                         ?>
                        <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>


                                <?php if($aValue['rtPartitionCST'] == 1){
                                  echo "<tr><td class='xCNRptGrouPing' colspan='8' >";
                                  echo "ผู้จำหน่าย : ".$aValue['FTSplName'];
                                  echo "</td>";
                                  echo "</tr>";
                                 } ?>
                                 <tr>
                                     <td  class="text-left xCNRptDetail"><?php echo $aValue['FTPdtCode']; ?></td>
                                     <td  class="text-left xCNRptDetail"><?php echo $aValue['FTPdtName']; ?></td>
                                     <td  class="text-right xCNRptDetail"><?php echo number_format($aValue['FCXpdQty'],$nOptDecimalShow); ?></td>
                                     <td  class="text-left xCNRptDetail"><?php echo $aValue['FTPunName']; ?></td>
                                     <td  class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXpdValue"],$nOptDecimalShow); ?></td>
                                     <td  class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXpdDis"],$nOptDecimalShow); ?></td>
                                     <td  class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXpdVat"],$nOptDecimalShow); ?></td>
                                     <td  class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXpdNetAmt"],$nOptDecimalShow); ?></td>
                                 </tr><?php
                                 if($aValue['rtPartitionCST'] == $aValue['rtPartitionCountCST']){
                                 echo "<tr>";
                                 echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-left xCNRptGrouPing'>";
                                 echo "รวม : ".$aValue['FTSplName'];
                                 echo "</td>";
                                 echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'> รายการ</td>";
                                 echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'></td>";
                                 echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'></td>";
                                 echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'>".number_format($aValue['FCXpdValue_SPL_Footer'],$nOptDecimalShow)."</td>";
                                 echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'>".number_format($aValue['FCXpdDis_SPL_Footer'],$nOptDecimalShow)."</td>";
                                 echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'>".number_format($aValue['FCXpdVat_SPL_Footer'],$nOptDecimalShow)."</td>";
                                 echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'>".number_format($aValue['FCXpdNetAmt_SPL_Footer'],$nOptDecimalShow)."</td>";
                                 echo "</tr>"; } ?>
                            <?php
                            $nFCXpdValueSum = $aValue['FCXpdValue_Footer'];
                            $nFCXpdDisSum = $aValue['FCXpdDis_Footer'];
                            $nFCXpdVatSum = $aValue['FCXpdVat_Footer'];
                            $nFCXpdNetAmtSum = $aValue['FCXpdNetAmt_Footer'];

                          } ?>

                            <?php
                            echo "<tr>";
                            echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-left xCNRptGrouPing'>";
                            echo "รวม";
                            echo "</td>";
                            echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'></td>";
                            echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'></td>";
                            echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'></td>";
                            echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'>".number_format($nFCXpdValueSum,$nOptDecimalShow)."</td>";
                            echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'>".number_format($nFCXpdDisSum,$nOptDecimalShow)."</td>";
                            echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'>".number_format($nFCXpdVatSum,$nOptDecimalShow)."</td>";
                            echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'>".number_format($nFCXpdNetAmtSum,$nOptDecimalShow)."</td>";
                            echo "</tr>"; ?>


                        <?php }else { ?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'></td></tr>
                        <?php } ;?>
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

            <?php if((isset($aDataFilter['tEffectiveDateFrom']) && !empty($aDataFilter['tEffectiveDateFrom'])) && (isset($aDataFilter['tEffectiveDateTo']) && !empty($aDataFilter['tEffectiveDateTo']))): ?>
                <!-- ===== ฟิวเตอร์ข้อมูล วันที่มีผล ======================ชชชชชชชชชชชชช==== -->
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="text-left">
                            <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptEffectiveDateFrom']?></label>   <label><?=date('d/m/Y',strtotime($aDataFilter['tEffectiveDateFrom']));?>  </label>&nbsp;
                            <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptEffectiveDateTo']?></label>     <label><?=date('d/m/Y',strtotime($aDataFilter['tEffectiveDateTo']));?>    </label>
                        </div>
                    </div>
                </div>
            <?php endif;?>

            <?php if( (isset($aDataFilter['tRptPdtCodeFrom']) && !empty($aDataFilter['tRptPdtCodeFrom'])) && (isset($aDataFilter['tRptPdtCodeTo']) && !empty($aDataFilter['tRptPdtCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล สินค้า =========================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeFrom'].' : </span>'.$aDataFilter['tRptPdtNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeTo'].' : </span>'.$aDataFilter['tRptPdtNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tRptPdtUnitCodeFrom']) && !empty($aDataFilter['tRptPdtUnitCodeFrom'])) && (isset($aDataFilter['tRptPdtUnitCodeTo']) && !empty($aDataFilter['tRptPdtUnitCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล หน่วยสินค้า ======================================= -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptPdtUnitFrom'].' : </span>'.$aDataFilter['tRptPdtUnitNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptPdtUnitTo'].' : </span>'.$aDataFilter['tRptPdtUnitNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tRptEffectivePriceGroupCodeFrom']) && !empty($aDataFilter['tRptEffectivePriceGroupCodeFrom'])) && (isset($aDataFilter['tRptEffectivePriceGroupCodeTo']) && !empty($aDataFilter['tRptEffectivePriceGroupCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล กลุ่มราคาที่มีผล =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptEffectivePriceGroupFrom'].' : </span>'.$aDataFilter['tRptEffectivePriceGroupNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptEffectivePriceGroupTo'].' : </span>'.$aDataFilter['tRptEffectivePriceGroupNameTo'];?></label>
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
