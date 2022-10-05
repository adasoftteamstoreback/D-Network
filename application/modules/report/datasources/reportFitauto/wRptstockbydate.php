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
            <div class="row">
                <?php include 'application\modules\report\datasources\Address\wRptAddress.php'; ?>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="text-center">
                        <label class="xCNRptTitle"><?php echo $aDataTextRef['tTitleReport']; ?></label>
                    </div>
                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) : ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSaleByCashierAndPosFilterDocDateFrom']; ?> </span> <?php echo date("d/m/Y", strtotime($aDataFilter['tDocDateFrom'])); ?></label>
                                    <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptSaleByCashierAndPosFilterDocDateTo']; ?> </span> <?php echo date("d/m/Y", strtotime($aDataFilter['tDocDateTo'])); ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

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
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:20%;" ><?php echo language('report/report/report', 'tRptstockbydateCol1'); ?> / คลังสินค้า</th>
                            <th nowrap class="text-left xCNRptColumnHeader" style="width:10%;"><?php echo language('report/report/report', 'tRptstockbydateCol2'); ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader"  ><?php echo language('report/report/report', 'tRptstockbydateCol3'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;" ><?php echo language('report/report/report', 'tRptstockbydateCol4'); ?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;" ><?php echo language('report/report/report', 'tRptstockbydateCol5'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])):?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <?php if($aValue['RowIDByBCH'] == 1){ ?>
                                    <tr><td class="xCNRptDetail xCNRptGrouPing"  colspan="5" style="border-top: dashed 1px #333 !important;">(<?=$aValue['FTBchCode']?>) <?=$aValue['FTBchName']; ?></td></tr>
                                <?php } ?>

                                <tr>
                                    <?php if($aValue['RowIDByWAH'] == 1){ ?>
                                        <td class="text-left xCNRptDetail xCNRptGrouPing">&nbsp;&nbsp;&nbsp;(<?=$aValue['FTWahCode']?>) <?=$aValue['FTWahName']; ?></td>
                                    <?php }else{ ?>
                                        <td></td>
                                    <?php } ?>

                                    <td class="text-left xCNRptDetail"><?=$aValue['FTPdtCode']; ?></td>
                                    <td class="text-left xCNRptDetail"><?=$aValue['FTPdtName']; ?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCStkQtyBal'],$nOptDecimalShow); ?></td>
                                    <td class="text-right xCNRptDetail"><?=number_format($aValue['FCStkCostStd'],$nOptDecimalShow); ?></td>
                                </tr>
                            <?php } ?>

                            <?php
                                //Summary Footer
                                $aDataTotal = $aDataReport['aRptData'][0];
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];

                            if ($nPageNo == $nTotalPage) { ?>
                                <tr class='xCNTrFooter'>
                                    <td class="text-left xCNRptDetail xCNRptGrouPing">รวมทั้งสิ้น</td>
                                    <td class="text-left "></td>
                                    <td class="text-left "></td>
                                    <td class="text-right xCNRptDetail xCNRptGrouPing"><?=number_format($aDataTotal['FCStkQtyBal_Footer'],$nOptDecimalShow); ?></td>
                                    <td class="text-right xCNRptDetail xCNRptGrouPing"><?=number_format($aDataTotal['FCStkCostStd_Footer'],$nOptDecimalShow); ?></td>
                                <tr>
                            <?php } ?>

                        <?php else:?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo @$aDataTextRef['tRptAdjStkNoData'];?></td></tr>
                        <?php endif;?>
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

             <!-- ============================ ฟิวเตอร์ข้อมูล คลังสินค้า ============================ -->
             <?php if ((isset($aDataFilter['tWahCodeFrom']) && !empty($aDataFilter['tWahCodeFrom'])) && (isset($aDataFilter['tWahCodeTo']) && !empty($aDataFilter['tWahCodeTo']))) : ?>
            <div class="xCNRptFilterBox">
                <div class="text-left xCNRptFilter">
                    <label class="xCNRptLabel xCNRptDisplayBlock"><span
                            class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjWahFrom'].' : </span>'.$aDataFilter['tWahNameFrom'];?></label>
                    <label class="xCNRptLabel xCNRptDisplayBlock"><span
                            class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjWahTo'].' : </span>'.$aDataFilter['tWahNameTo'];?></label>
                </div>
            </div>
            <?php endif; ?>

            <!-- ============================ ฟิวเตอร์ข้อมูล ปี เดือน ============================ -->
            <?php if ((isset($aDataFilter['tRptYearCode']) && !empty($aDataFilter['tRptYearCode'])) && isset($aDataFilter['tMonth']) && !empty($aDataFilter['tMonth'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label>
                            <span class="xCNRptFilterHead">วันที่ : </span> <?=$aDataFilter['tDayFrom']; ?>
                            <span class=""> - </span> <?=$aDataFilter['tDayTo']; ?>
                        </label>&nbsp;&nbsp;
                        <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptMonth']; ?> </span> <?=language('report/report/report', 'tRptMonth'.$aDataFilter['tMonth'])?></label>&nbsp;&nbsp;
                        <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptYear']; ?> </span> <?= $aDataFilter['tRptYearCode']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <?php if( (isset($aDataFilter['tPdtCodeFrom']) && !empty($aDataFilter['tPdtCodeFrom'])) && (isset($aDataFilter['tPdtCodeTo']) && !empty($aDataFilter['tPdtCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล สินค้า =========================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeFrom'].' : </span>'.$aDataFilter['tPdtNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeTo'].' : </span>'.$aDataFilter['tPdtNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

        </div>
    </div>
</div>
