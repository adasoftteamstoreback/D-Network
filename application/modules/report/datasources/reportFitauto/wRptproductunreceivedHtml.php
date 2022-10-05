<?php
$aDataReport    = $aDataViewRpt['aDataReport'];
$aDataTextRef   = $aDataViewRpt['aDataTextRef'];
$aDataFilter    = $aDataViewRpt['aDataFilter'];
?>
<style>
    .table thead th,
    .table>thead>tr>th,
    .table tbody tr,
    .table>tbody>tr>td {
        border: 0px transparent !important;
    }

    .table>thead:first-child>tr:first-child>td,
    .table>thead:first-child>tr:first-child>th {
        border-top: 1px solid black !important;
    }

    /** แนวตั้ง */
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
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center">
                                <label class="xCNRptTitle"><?php echo @$aDataTextRef['tTitleReport']; ?></label>
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
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjDateFrom'] ?> : </label> <label><?= date('d/m/Y', strtotime($aDataFilter['tDocDateFrom'])); ?></label>&nbsp;
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjDateTo'] ?> : </label> <label><?= date('d/m/Y', strtotime($aDataFilter['tDocDateTo'])); ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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
                            <th nowrap class="text-center xCNRptColumnHeader" rowspan="2" style="vertical-align: middle; border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptDateDocument'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" rowspan="2" style="vertical-align: middle; border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptSRCBch'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" rowspan="2" style="vertical-align: middle; border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptPerchaseOrderDocNo'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" rowspan="2" style="vertical-align: middle; border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptRefDeliveryOrder'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" rowspan="2" style="vertical-align: middle; border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptPdtCode'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" rowspan="2" style="vertical-align: middle; border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptPdtName'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" colspan="3" style="border-right: 1px solid #333 !important; border-bottom: solid 1px #333 !important;"><?php echo language('report/report/report', 'tRptQtyLeft'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" rowspan="2" style="vertical-align: middle; border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptUnit'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" rowspan="2" style="vertical-align: middle; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRPC4TBCvdRmk'); ?></th>
                        </tr>
                        <tr>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptOrder'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptReceived'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="border-right: 1px solid #333 !important; border-bottom: solid 1px black !important;"><?php echo language('report/report/report', 'tRptUnreceived'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <tr>
                                    <?php if ($aValue['PARTITION_DATE'] == 1) { ?>
                                        <td class='xCNRptDetail text-center SUMDate' rowspan="<?=$aValue['ALLDocINDate']?>"><?=date("d/m/Y", strtotime($aValue['FDXphDocDate']))?></td>
                                    <?php } ?>
                                    <?php if ($aValue['PARTITION_PO'] == 1) { ?>
                                        <?php
                                            if($aValue['FTXshRefDO'] != '' && !empty($aValue['FTXshRefDO'])){
                                                $aTextDocRefexplode = str_replace(",","<br>",$aValue['FTXshRefDO']);
                                            }else{
                                                $aTextDocRefexplode = '';
                                            }    
                                        ?>
                                        <td class='xCNRptDetail text-left' rowspan="<?=$aValue['MAX_PO']?>"><?=$aValue['FTBchName']?></td>
                                        <td class='xCNRptDetail text-left' rowspan="<?=$aValue['MAX_PO']?>"><?=$aValue['FTXphDocNo']?></td>
                                        <td class='xCNRptDetail text-left' rowspan="<?=$aValue['MAX_PO']?>"><?=$aTextDocRefexplode?></td>
                                    <?php } ?>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTPdtCode']?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTPdtName']?></td>
                                    <td class='xCNRptDetail text-center'><?=number_format($aValue['FCXpdQty'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-center'><?=number_format($aValue['FCXpdQtyRcv'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-center'><?=number_format($aValue['FCXpdQtyLef'], $nOptDecimalShow)?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTPunName']?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTXpdRmk']?></td>
                                </tr>
                                <?php if ($aValue['PARTITION_PO'] == $aValue['MAX_PO']) { ?>
                                    <tr class="SUMPO" style="border-bottom: 1px solid #ddd !important;">
                                        <td class='xCNRptDetail text-left'><?//=$aValue['FTBchName']?></td>
                                        <td class='xCNRptSumFooter text-left'><?=language('report/report/report', 'tRptEndDayTotal');?> <?=$aValue['FTXphDocNo']?></td>
                                        <td class='xCNRptDetail text-left'><?//=$aValue['FTXshRefDO']?></td>
                                        <td class='xCNRptDetail text-left'><?//=$aValue['FTPdtCode']?></td>
                                        <td class='xCNRptDetail text-left'><?//=$aValue['FTPdtName']?></td>
                                        <td class='xCNRptSumFooter text-center'><?=number_format($aValue['Qty_DocNo_Footer'], $nOptDecimalShow)?></td>
                                        <td class='xCNRptSumFooter text-center'><?=number_format($aValue['QtyRcv_DocNo_Footer'], $nOptDecimalShow)?></td>
                                        <td class='xCNRptSumFooter text-center'><?=number_format($aValue['QtyLef_DocNo_Footer'], $nOptDecimalShow)?></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($aValue['PARTITION_DATE'] == $aValue['MAX_DATE']) { ?>
                                    <tr style="border-bottom: 1px solid #ddd !important;">
                                        <td class='xCNRptSumFooter text-center'><?=language('report/report/report', 'tRptEndDayTotal');?> <?=date("d/m/Y", strtotime($aValue['FDXphDocDate']))?></td>
                                        <td class='xCNRptDetail text-left'><?//=$aValue['FTBchName']?></td>
                                        <td class='xCNRptDetail text-left'></td>
                                        <td class='xCNRptDetail text-left'><?//=$aValue['FTXshRefDO']?></td>
                                        <td class='xCNRptDetail text-left'><?//=$aValue['FTPdtCode']?></td>
                                        <td class='xCNRptDetail text-left'><?//=$aValue['FTPdtName']?></td>
                                        <td class='xCNRptSumFooter text-center'><?=number_format($aValue['Qty_DocDate_Footer'], $nOptDecimalShow) ?></td>
                                        <td class='xCNRptSumFooter text-center'><?=number_format($aValue['QtyRcv_DocDate_Footer'], $nOptDecimalShow)?></td>
                                        <td class='xCNRptSumFooter text-center'><?=number_format($aValue['QtyLef_DocDate_Footer'], $nOptDecimalShow)?></td>
                                    </tr>
                                <?php } ?>
                                

                            <?php endforeach; ?>
                            <?php 
                                $aDataTotal = $aDataReport['aRptData'][0];
                            
                                //Step 6 : สั่ง Summary Footer
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                            
                            ?>
                            <?php if ($nPageNo == $nTotalPage) { ?>
                                <tr class='xCNTrFooter'><tr>
                            <?php } ?>
                            
                        <?php else : ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?php echo $aDataTextRef['tRptAdjStkNoData']; ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="row" >
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <div class="xCNRptFilterTitle">
                        <div class="text-left">
                            <label class="xCNTextConsOth"><?= $aDataTextRef['tRptConditionInReport']; ?></label>
                        </div>
                    </div>
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

            <?php if( (isset($aDataFilter['tPdtSupplierCodeFrom']) && !empty($aDataFilter['tPdtSupplierCodeFrom'])) && (isset($aDataFilter['tPdtSupplierCodeTo']) && !empty($aDataFilter['tPdtSupplierCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ผู้จำหน่าย =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplFrom'].' : </span>'.$aDataFilter['tPdtSupplierNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTo'].' : </span>'.$aDataFilter['tPdtSupplierNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tPdtSgpCodeFrom']) && !empty($aDataFilter['tPdtSgpCodeFrom'])) || (isset($aDataFilter['tPdtSgpCodeTo']) && !empty($aDataFilter['tPdtSgpCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล กลุ่มผู้จำหน่าย =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplGrpForm'].' : </span>'.$aDataFilter['tPdtSgpNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplGrpTo'].' : </span>'.$aDataFilter['tPdtSgpNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tPdtStyCodeFrom']) && !empty($aDataFilter['tPdtStyCodeFrom'])) || (isset($aDataFilter['tPdtStyCodeTo']) && !empty($aDataFilter['tPdtStyCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ประเภทผู้จำหน่าย =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTypeForm'].' : </span>'.$aDataFilter['tPdtStyNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSplTypeTo'].' : </span>'.$aDataFilter['tPdtStyNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>
        </div>
    </div>
</div>

<script>

</script>