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
        border-bottom: solid 1px #333 !important;
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

                    <?php if ((isset($aDataFilter['tRptMFGFrom']) && !empty($aDataFilter['tRptMFGFrom'])) && (isset($aDataFilter['tRptMFGTo']) && !empty($aDataFilter['tRptMFGTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่ผลิต ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptDateMFGF') ?> : </label> <label><?=$aDataFilter['tRptMFGFrom']?></label>&nbsp;
                                    <label class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptDateMFGT') ?> : </label> <label><?=$aDataFilter['tRptMFGTo']?></label>
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
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPdtBrandCode'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPdtBrandName'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPdtModelCode'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptPdtModelName'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptDotCode'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptDotName'); ?></th>
                            <th nowrap class="text-center xCNRptColumnHeader"><?php echo language('report/report/report', 'tRptDotYear'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php $tKeepPbnNo = ''; ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <tr
                                    <?php if($aValue['PARTITIONBYBRAND'] == $aValue['PARTITION_PDT'] ) { ?>
                                        style='border-bottom: 1px solid #ddd !important;'
                                    <?php } ?>
                                >
                                <?php  
                                    //รวมคอลัมน์
                                    if($aValue['PARTITIONBYBRAND'] == 1 || $aValue['PARTITIONBYBRAND'] == 0){
                                        $nRowspan   = '';
                                    }else{
                                        $nRowspan   = "rowspan=".$aValue['PARTITIONBYBRAND'];
                                    } 

                                    $tBrandCode = '';
                                    if (!empty($aValue['FTPbnCode']) && $aValue['FTPbnCode'] != '') {
                                        $tBrandCode = $aValue['FTPbnCode'];
                                    }else{
                                        $tBrandCode = '-';
                                    }

                                    $tBrandName = '';
                                    if (!empty($aValue['FTPbnName']) && $aValue['FTPbnName'] != '') {
                                        $tBrandName = $aValue['FTPbnName'];
                                    }else{
                                        $tBrandName = '-';
                                    }

                                    $tModelCode = '';
                                    if (!empty($aValue['FTPmoCode']) && $aValue['FTPmoCode'] != '') {
                                        $tModelCode = $aValue['FTPmoCode'];
                                    }else{
                                        $tModelCode = '-';
                                    }

                                    $tModelName = '';
                                    if (!empty($aValue['FTPmoName']) && $aValue['FTPmoName'] != '') {
                                        $tModelName = $aValue['FTPmoName'];
                                    }else{
                                        $tModelName = '-';
                                    }
                                ?>
                                
                                    <?php if($tKeepPbnNo != $aValue['FTPbnCode'] ) { ?>
                                        <td class='xCNRptDetail text-left' <?=$nRowspan?>><?=$tBrandCode?></td>
                                        <td class='xCNRptDetail text-left' <?=$nRowspan?>><?=$tBrandName?></td>
                                    <?php }else if($tKeepPbnNo == 0){ ?>
                                        <td class='xCNRptDetail text-left'><?=$tBrandCode?></td>
                                        <td class='xCNRptDetail text-left'><?=$tBrandName?></td>
                                    <?php } ?>
                                        <td class='xCNRptDetail text-left'><?=$tModelCode?></td>
                                        <td class='xCNRptDetail text-left'><?=$tModelName?></td>
                                        <td class='xCNRptDetail text-left'><?=$aValue['FTLotNo']?></td>
                                        <td class='xCNRptDetail text-left'><?=$aValue['FTLotBatchNo']?></td>
                                        <td class='xCNRptDetail text-center'><?=!empty($aValue['FTLotYear']) ? $aValue['FTLotYear'] : "-"?></td>
                                </tr>
                            <?php $tKeepPbnNo = $aValue['FTPbnCode']; ?>
                            <?php endforeach; ?>
                            <?php

                            //Summary Footer
                            $aDataTotal = $aDataReport['aRptData'][0];
                            $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];

                            if ($nPageNo == $nTotalPage) { ?>
                                <tr class='xCNTrFooter'><tr>
                            <?php } ?>
                            
                        <?php else : ?>
                            <tr>
                                <td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('report/report/report', 'tRptAdjStkNoData');?></td>
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
            <?php if( (isset($aDataFilter['tRptPdtCodeFrom']) && !empty($aDataFilter['tRptPdtCodeFrom'])) && (isset($aDataFilter['tRptPdtCodeTo']) && !empty($aDataFilter['tRptPdtCodeTo']))) { ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tPdtCodeFrom').' : </span>'.$aDataFilter['tRptPdtNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tPdtCodeTo').' : </span>'.$aDataFilter['tRptPdtNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>
            <!-- ========================================================================= -->

            <!-- ===== ฟิวเตอร์ข้อมูล ยี่ห้อสินค้า =================================== -->
            <?php if( (isset($aDataFilter['tRptPdtBrandCodeFrom']) && !empty($aDataFilter['tRptPdtBrandCodeFrom'])) && (isset($aDataFilter['tRptPdtBrandCodeTo']) && !empty($aDataFilter['tRptPdtBrandCodeTo']))) { ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptBrandFrom').' : </span>'.$aDataFilter['tRptPdtBrandNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptBrandTo').' : </span>'.$aDataFilter['tRptPdtBrandNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>
            <!-- ========================================================================= -->

            <!-- ===== ฟิวเตอร์ข้อมูล รุ่นสินค้า =================================== -->
            <?php if( (isset($aDataFilter['tRptPdtModelCodeFrom']) && !empty($aDataFilter['tRptPdtModelCodeFrom'])) && (isset($aDataFilter['tRptPdtModelCodeTo']) && !empty($aDataFilter['tRptPdtModelCodeTo']))) { ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptModelFrom').' : </span>'.$aDataFilter['tRptPdtModelNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptModelTo').' : </span>'.$aDataFilter['tRptPdtModelNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>
            <!-- ========================================================================= -->

            <!-- ===== ฟิวเตอร์ข้อมูล ชื่อ Dot ยาง =================================== -->
            <?php if( (isset($aDataFilter['tRptPdtDotCodeFrom']) && !empty($aDataFilter['tRptPdtDotCodeFrom'])) && (isset($aDataFilter['tRptPdtDotCodeTo']) && !empty($aDataFilter['tRptPdtDotCodeTo']))) { ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptPdtDotF').' : </span>'.$aDataFilter['tRptPdtDotNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo language('report/report/report', 'tRptPdtDotT').' : </span>'.$aDataFilter['tRptPdtDotNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>
            <!-- ========================================================================= -->
        </div>
    </div>
</div>
