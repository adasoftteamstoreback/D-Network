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
        border-bottom: 1px solid black !important;
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

                    <?php if ((isset($aDataFilter['tRptEffectiveDateFrom']) && !empty($aDataFilter['tRptEffectiveDateFrom'])) && (isset($aDataFilter['tRptEffectiveDateTo']) && !empty($aDataFilter['tRptEffectiveDateTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่ ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptEffectiveDateFrom');?> : </label> <label><?= date('d/m/Y', strtotime($aDataFilter['tRptEffectiveDateFrom'])); ?></label>&nbsp;
                                    <label class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptEffectiveDateTo');?> : </label> <label><?= date('d/m/Y', strtotime($aDataFilter['tRptEffectiveDateTo'])); ?></label>
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
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRowNumber'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptBarchCode'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRPC15TBBchName'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptChangePriceDocNo'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptAPBGDStart'); ?></th>
                            <!-- <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptDateTo'); ?></th> -->
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptTimeEffectF'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptTimeEffectT'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptAPBGPriceGroup'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPdtCode'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPdtName'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptUnit'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptInventoriesByBchPrice'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <tr>
                                    <td class='xCNRptDetail text-right'><?=@$i+=1?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTBchCode']?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTBchName']?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTXphDocNo']?></td>
                                    <td class='xCNRptDetail text-center'><?=date("d/m/Y", strtotime($aValue['FDXphDStart']))?></td>
                                    <!-- <td class='xCNRptDetail text-center'><?=date("d/m/Y", strtotime($aValue['FDXphDStop']))?></td> -->
                                    <td class='xCNRptDetail text-center'><?=$aValue['FTXphTStart']?></td>
                                    <td class='xCNRptDetail text-center'><?=$aValue['FTXphTStop']?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTPplName']?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTPdtCode']?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTPdtName']?></td>
                                    <td class='xCNRptDetail text-left'><?=$aValue['FTPunName']?></td>
                                    <td class='xCNRptDetail text-right'><?=number_format($aValue['FCXpdPriceRet'], $nOptDecimalShow)?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php $i++ ?>
                        <?php else : ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?php echo $aDataTextRef['tRptNoData']; ?></td>
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
            <!-- ========================================================================= -->

            <!-- ===== ฟิวเตอร์ข้อมูล รหัสสินค้า =================================== -->
            <?php if( (isset($aDataFilter['tRptPdtPdtCodeFrom']) && !empty($aDataFilter['tRptPdtPdtCodeFrom'])) && (isset($aDataFilter['tRptPdtPdtCodeTo']) && !empty($aDataFilter['tRptPdtPdtCodeTo']))) { ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tPdtCodeFrom').' : </span>'.$aDataFilter['tRptPdtPdtNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tPdtCodeTo').' : </span>'.$aDataFilter['tRptPdtPdtNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>
            <!-- ========================================================================= -->

            <!-- ===== ฟิวเตอร์ข้อมูล กลุ่มราคาสินค้า =================================== -->
            <?php if( (isset($aDataFilter['tRptEffectivePriceGroupCodeFrom']) && !empty($aDataFilter['tRptEffectivePriceGroupCodeFrom'])) && (isset($aDataFilter['tRptEffectivePriceGroupCodeTo']) && !empty($aDataFilter['tRptEffectivePriceGroupCodeTo']))) { ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptEffectivePriceGroupFrom').' : </span>'.$aDataFilter['tRptEffectivePriceGroupNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptEffectivePriceGroupTo').' : </span>'.$aDataFilter['tRptEffectivePriceGroupNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>
            <!-- ========================================================================= -->
        </div>
    </div>
</div>
