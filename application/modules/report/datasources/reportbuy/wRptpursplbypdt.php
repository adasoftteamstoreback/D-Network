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
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo "รหัสสินค้า";?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:15%;"><?php echo "ชื่อสินค้า";?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo "จำนวน";?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%;"><?php echo "หน่วย";?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:5%;"><?php echo "มูลค่า";?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:5%;"><?php echo "ส่วนลด";?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:5%;"><?php echo "ภาษี";?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo "รวม";?></th>
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
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                                if (isset($aData[$aValue["FTSplCode"]])) {
                                  $aData[$aValue["FTSplCode"]][] = $aValue;
                                  $aSplName[$aValue["FTSplCode"]][] =  $aValue['FTSplName'];
                                }else {
                                  $aData[$aValue["FTSplCode"]] = array();
                                  $aSplName[$aValue["FTSplCode"]] = array();
                                  $aData[$aValue["FTSplCode"]][] = $aValue;
                                  $aSplName[$aValue["FTSplCode"]][] =  $aValue['FTSplName'];
                                }
                              } ?>

                            <?php
                              foreach ($aSplName as $nKey => $aValue) {
                                echo "<tr><td class='xCNRptGrouPing' colspan='8' >";
                                echo "ผู้จำหน่าย : ".$aValue[0];
                                echo "</td>";
                                echo "</tr>";

                                $nFCXpdValue = 0;
                                $nFCXpdDis = 0;
                                $nFCXpdVat = 0;
                                $nFCXpdNetAmt = 0;
                                $nFCXpdQty = 0;
                                foreach ($aData[$nKey] as $key => $value) {  ?>
                                  <tr>
                                      <td  class="text-left xCNRptDetail"><?php echo $value['FTPdtCode']; ?></td>
                                      <td  class="text-left xCNRptDetail"><?php echo $value['FTPdtName']; ?></td>
                                      <td  class="text-right xCNRptDetail"><?php echo number_format($value['FCXpdQty'],$nOptDecimalShow); ?></td>
                                      <td  class="text-left xCNRptDetail"><?php echo $value['FTPunName']; ?></td>
                                      <td  class="text-right xCNRptDetail"><?php echo number_format($value["FCXpdValue"],$nOptDecimalShow); ?></td>
                                      <td  class="text-right xCNRptDetail"><?php echo number_format($value["FCXpdDis"],$nOptDecimalShow); ?></td>
                                      <td  class="text-right xCNRptDetail"><?php echo number_format($value["FCXpdVat"],$nOptDecimalShow); ?></td>
                                      <td  class="text-right xCNRptDetail"><?php echo number_format($value["FCXpdNetAmt"],$nOptDecimalShow); ?></td>
                                  </tr><?php
                                  $nFCXpdQty +=$value['FCXpdQty'];
                                  $nFCXpdValue += $value["FCXpdValue"];
                                  $nFCXpdDis += $value["FCXpdDis"];
                                  $nFCXpdVat += $value["FCXpdVat"];
                                  $nFCXpdNetAmt += $value["FCXpdNetAmt"];

                                  $nFCXpdValueSum +=$value['FCXpdValue'];
                                  $nFCXpdDisSum +=$value["FCXpdDis"];
                                  $nFCXpdVatSum +=$value["FCXpdVat"];
                                  $nFCXpdNetAmtSum +=$value["FCXpdNetAmt"];
                                  $nFCXpdQtySum +=$value["FCXpdQty"];
                                }
                                echo "<tr>";
                                echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-left xCNRptGrouPing'>";
                                echo "รวม : ".$aValue[0];
                                echo "</td>";
                                echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'>".count($aData[$nKey])." รายการ</td>";
                                echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'></td>";
                                echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'></td>";
                                echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'>".number_format($nFCXpdValue,$nOptDecimalShow)."</td>";
                                echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'>".number_format($nFCXpdDis,$nOptDecimalShow)."</td>";
                                echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'>".number_format($nFCXpdVat,$nOptDecimalShow)."</td>";
                                echo "<td style='border-top: dashed 1px #333 !important;border-bottom: solid 1px #333 !important;' class='text-right xCNRptGrouPing'>".number_format($nFCXpdNetAmt,$nOptDecimalShow)."</td>";
                                echo "</tr>";
                              }


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

            <?php if((isset($aDataFilter['tPdtDateFrom']) && !empty($aDataFilter['tPdtDateFrom']))): ?>
                <!-- ===== ฟิวเตอร์ข้อมูล วันที่มีผล ========================= -->
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="text-left">
                            <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateFrom']?></label>   <label><?=date('d/m/Y',strtotime($aDataFilter['tPdtDateFrom']));?>  </label>
                        </div>
                    </div>
                </div>
            <?php endif;?>

            <?php if( (isset($aDataFilter['tPdtSupplierCodeFrom']) && !empty($aDataFilter['tPdtSupplierCodeFrom'])) && (isset($aDataFilter['tPdtSupplierCodeTo']) && !empty($aDataFilter['tPdtSupplierCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ผู้จำหน่าย =========================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplFrom'].' : </span>'.$aDataFilter['tPdtSupplierNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTo'].' : </span>'.$aDataFilter['tPdtSupplierNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tPdtGrpCodeFrom']) && !empty($aDataFilter['tPdtGrpCodeFrom'])) && (isset($aDataFilter['tPdtGrpCodeTo']) && !empty($aDataFilter['tPdtGrpCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล กลุ่มสินค้า =========================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtGrpFrom'].' : </span>'.$aDataFilter['tPdtGrpNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtGrpTo'].' : </span>'.$aDataFilter['tPdtGrpNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tPdtTypeCodeFrom']) && !empty($aDataFilter['tPdtTypeCodeFrom'])) && (isset($aDataFilter['tPdtTypeCodeTo']) && !empty($aDataFilter['tPdtTypeCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ประเภทสินค้า ======================================= -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtTypeFrom'].' : </span>'.$aDataFilter['tPdtTypeCodeFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtTypeTo'].' : </span>'.$aDataFilter['tPdtTypeCodeTo'];?></label>
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
