<?php
    $aDataReport        = $aDataViewRpt['aDataReport'];
    $aDataTextRef       = $aDataViewRpt['aDataTextRef'];
    $aDataFilter        = $aDataViewRpt['aDataFilter'];
    $nOptDecimalShow    = get_cookie('tOptDecimalShow');
?>
<style type="text/css">
    .xCNFooterRpt{ border-bottom : 7px double #ddd;} .table thead th, .table>thead>tr>th, .table tbody tr, .table>tbody>tr>td{ border: 0px transparent !important;} .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th{ border-top: 1px solid black !important; border-bottom : 1px solid black !important; } .table>tbody>tr.xCNTrSubFooter{ border-top: 1px solid black !important; border-bottom : 1px solid black !important; } .table>tbody>tr.xCNTrFooter{ border-top: dashed 1px #333 !important; border-bottom : 1px solid black !important; } @media print{@page{size: landscape; margin: 1.5mm 1.5mm 1.5mm 1.5mm;}}
</style>
<div id="odvRptTaxSalePosHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <div class="row">
                <?php include 'application\modules\report\datasources\Address\wRptAddress.php'; ?>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 report-filter">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center">
                                <label class="xCNRptTitle"><?= $aDataTextRef['tTitleReport']; ?></label>
                            </div>
                            <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) : ?>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="text-center xCNRptFilter">
                                            <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateFrom']; ?> </span> <?php echo date("d/m/Y", strtotime($aDataFilter['tDocDateFrom'])); ?></label>
                                            <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateTo']; ?> </span> <?php echo date("d/m/Y", strtotime($aDataFilter['tDocDateTo'])); ?></label>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <label class="xCNRptDataPrint"><?= $aDataTextRef['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $aDataTextRef['tTimePrint'] . ' ' . date('H:i:s'); ?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th nowrap class="text-left  xCNRptColumnHeader"  style="width:10%;"><?php echo $aDataTextRef['tRptPdtCode'];?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"  style="width:15%;"><?php echo $aDataTextRef['tRptPdtName'];?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader"  style="width:15%;"><?php echo $aDataTextRef['tRptPdtGrp'];?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo $aDataTextRef['tRptSaleQty'];?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo $aDataTextRef['tRptCabinetCost'];?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo $aDataTextRef['tRptTotalSale'];?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo $aDataTextRef['tRptProfitandLost'];?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo $aDataTextRef['tRptCapital'];?></th>
                            <th nowrap class="text-right xCNRptColumnHeader" style="width:10%;"><?php echo $aDataTextRef['tRptGrandtotal'];?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) : ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) : ?>
                                <tr>
                                    <td nowrap class="text-left xCNRptDetail"><?php echo $aValue["FTPdtCode"];?></td>
                                    <td nowrap class="text-left xCNRptDetail"><?php echo $aValue["FTPdtName"];?></td>
                                    <td nowrap class="text-left xCNRptDetail"><?php echo $aValue["FTChainName"]; ?></td>
                                    <td nowrap class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXsdSaleQty"],$nOptDecimalShow)?></td>
                                    <td nowrap class="text-right xCNRptDetail"><?php echo number_format($aValue["FCPdtCost"],$nOptDecimalShow)?></td>
                                    <td nowrap class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXshGrand"],$nOptDecimalShow)?></td>
                                    <td nowrap class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXsdProfit"],$nOptDecimalShow)?></td>
                                    <td nowrap class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXsdProfitPercent"],$nOptDecimalShow)?></td>
                                    <td nowrap class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXsdSalePercent"],$nOptDecimalShow)?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php 
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                            ?>
                            <?php if ($nPageNo == $nTotalPage): ?>
                                <tr>
                                    <td nowrap class="xCNRptDetail"><strong>รวม<strong></td>
                                    <td nowrap class="xCNRptDetail" colspan="2"></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXsdSaleQty_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCPdtCost_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXshGrand_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXsdProfit_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXsdProfitPercent_Footer'], $nOptDecimalShow) ?></strong></td>
                                    <td nowrap class="xCNRptDetail text-right"><strong><?= number_format($aDataReport['aRptData'][0]['FCXsdSalePercent_Footer'], $nOptDecimalShow) ?></strong></td>
                                </tr>
                                <tr class="text-left xCNRptDetail" style=" border-left: 11px dashed black !important; border-bottom: dashed 1px #333 !important;"></tr>
                            <?php endif;?>
                        <?php else : ?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo @$aDataTextRef['tRptAdjStkNoData']; ?></td></tr>
                        <?php endif; ?>
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

            <!-- ============================ ฟิวเตอร์ข้อมูล กลุ่มธุรกิจ ============================ -->
            <?php if ((isset($aDataFilter['tRptMerCodeFrom']) && !empty($aDataFilter['tRptMerCodeFrom'])) && (isset($aDataFilter['tRptMerCodeTo']) && !empty($aDataFilter['tRptMerCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjMerChantFrom'].' : </span>'.$aDataFilter['tRptMerNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjMerChantTo'].' : </span>'.$aDataFilter['tRptMerNameTo'];?></label>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($aDataFilter['tMerCodeSelect']) && !empty($aDataFilter['tMerCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptMerFrom']; ?> : </span> <?php echo ($aDataFilter['bMerStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tMerNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ============================ ฟิวเตอร์ข้อมูล ร้านค้า ============================ -->
            <?php if ((isset($aDataFilter['tRptShpCodeFrom']) && !empty($aDataFilter['tRptShpCodeFrom'])) && (isset($aDataFilter['tRptShpCodeTo']) && !empty($aDataFilter['tRptShpCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjShopFrom'].' : </span>'.$aDataFilter['tRptShpNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjShopTo'].' : </span>'.$aDataFilter['tRptShpNameTo'];?></label>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($aDataFilter['tShpCodeSelect']) && !empty($aDataFilter['tShpCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptShopFrom']; ?> : </span> <?php echo ($aDataFilter['bShpStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tShpNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ============================ ฟิวเตอร์ข้อมูล จุดขาย ============================ -->
            <?php if ((isset($aDataFilter['tRptPosCodeFrom']) && !empty($aDataFilter['tRptPosCodeFrom'])) && (isset($aDataFilter['tRptPosCodeTo']) && !empty($aDataFilter['tRptPosCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjPosFrom'].' : </span>'.$aDataFilter['tRptPosCodeFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjPosTo'].' : </span>'.$aDataFilter['tRptPosCodeTo'];?></label>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($aDataFilter['tPosCodeSelect']) && !empty($aDataFilter['tPosCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptPosFrom']; ?> : </span> <?php echo ($aDataFilter['bPosStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tPosCodeSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <?php if( (isset($aDataFilter['tRptPdtCodeFrom']) && !empty($aDataFilter['tRptPdtCodeFrom'])) && (isset($aDataFilter['tRptPdtCodeTo']) && !empty($aDataFilter['tRptPdtCodeTo']))) { ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล สินค้า ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeFrom'].' : </span>'.$aDataFilter['tRptPdtNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeTo'].' : </span>'.$aDataFilter['tRptPdtNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tRptPdtGrpCodeFrom']) && !empty($aDataFilter['tRptPdtGrpCodeFrom'])) && (isset($aDataFilter['tRptPdtGrpCodeTo']) && !empty($aDataFilter['tRptPdtGrpCodeTo']))) { ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล กลุ่มสินค้า ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtGrpFrom'].' : </span>'.$aDataFilter['tRptPdtGrpNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtGrpTo'].' : </span>'.$aDataFilter['tRptPdtGrpNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>


            <?php if( (isset($aDataFilter['tRptPdtTypeCodeFrom']) && !empty($aDataFilter['tRptPdtTypeCodeFrom'])) && (isset($aDataFilter['tRptPdtTypeCodeTo']) && !empty($aDataFilter['tRptPdtTypeCodeTo']))) { ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล ประเภทสินค้า ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtTypeFrom'].' : </span>'.$aDataFilter['tRptPdtTypeNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtTypeTo'].' : </span>'.$aDataFilter['tRptPdtTypeNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <!-- ============================ ฟิวเตอร์ข้อมูล tRptPosType ============================ -->
            <div class="xCNRptFilterBox">
                <div class="text-left xCNRptFilter">
                    <label class="xCNRptLabel xCNRptDisplayBlock" ><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptPosTypeName'].' : </span>'.$aDataTextRef['tRptPosType'.$aDataFilter['tPosType']]; ?></label>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var oFilterLabel    = $('.report-filter .text-left label:first-child');
        var nMaxWidth       = 0;
        oFilterLabel.each(function(index){
            var nLabelWidth = $(this).outerWidth();
            if(nLabelWidth > nMaxWidth){
                nMaxWidth = nLabelWidth;
            }
        });
        $('.report-filter .text-left label:first-child').width(nMaxWidth + 50);
    });
</script>