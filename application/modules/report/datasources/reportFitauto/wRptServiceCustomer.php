<?php
    $aDataReport        = $aDataViewRpt['aDataReport'];
    $aDataTextRef       = $aDataViewRpt['aDataTextRef'];
    $aDataFilter        = $aDataViewRpt['aDataFilter'];
    $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
?>
<style>
    .xCNFooterRpt {
        border-bottom   : 7px double #ddd;
    }

    .table thead th, .table>thead>tr>th, .table tbody tr, .table>tbody>tr>td {
        border          : 0px transparent !important;
    }

    .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
        border-top      : 1px solid black !important;
        border-bottom   : 1px solid black !important;
    }

    .table>tbody>tr.xCNTrSubFooter{
        border-top      : 1px solid black !important;
        border-bottom   : 1px solid black !important;
    }

    .table>tbody>tr.xCNTrFooter{
        border-top      : dashed 1px #333 !important;
        border-bottom   : 1px solid black !important;
    }

    /*แนวนอน*/
    @media print{@page {size: landscape;
        margin: 1.5mm 1.5mm 1.5mm 1.5mm;
    }}

</style>

<div id="odvRptPreviewCustomerHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <div class="row">
                <?php include 'application\modules\report\datasources\Address\wRptAddress.php'; ?>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 report-filter">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center">
                                <label class="xCNRptTitle"><?=$aDataTextRef['tTitleReport'];?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <label class="xCNRptDataPrint"><?=$aDataTextRef['tDatePrint'].' '.date('d/m/Y').' '.$aDataTextRef['tTimePrint'].' '.date('H:i:s');?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:8%;"><?=language('report/report/report','tRptBarchCode');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptSalByBranchBchName');?></th>
                            <th nowrap class="text-center  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptCstLostContLastSer');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:20%;"><?=language('report/report/report','tRptCstLostContName');?>-<?=language('report/report/report','tRptCstLostContLName');?></th> 
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:8%;"><?=language('report/report/report','tRptCstLostContContactNum');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptCstLostContLastSerNo');?></th>
                            <th nowrap class="text-center  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptCstLostContSatisfact');?></th>
                            <th nowrap class="text-center  xCNRptColumnHeader" style="width:15%;"><?=language('customer/customer/customer','tRPCCstForCastNextCycle');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <tr>
                                    <td class="xCNRptDetail"><?= ($aValue['FTBchCode']== '') ? '-' : $aValue['FTBchCode']?></td>
                                    <td class="xCNRptDetail"><?= ($aValue['FTBchName']== '') ? '-' : $aValue['FTBchName']?></td>
                                    <td class="xCNRptDetail text-center"><?=date('d/m/Y', strtotime($aValue['FDXshDocDate']));?></td>
                                    <td class="xCNRptDetail"><?= ($aValue['FTCstName']== '') ? '-' : $aValue['FTCstName']?></td>
                                    <td class="xCNRptDetail"><?= ($aValue['FTCstTel']== '') ? '-' : $aValue['FTCstTel'] ?></td>
                                    <td class="xCNRptDetail"><?= ($aValue['FTXshDocNo'] == '') ? '-' : $aValue['FTXshDocNo']?></td>
                                    <td class="xCNRptDetail text-center"><?=$aValue['FNXshScoreValue']?></td>
                                    <td class="xCNRptDetail text-center"><?=date('d/m/Y', strtotime($aValue['FDFlwDateForcast']));?></td>
                                </tr>
                            <?php } ?>
                        <?php }else { ?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?=$aDataTextRef['tRptNoData'];?></td></tr>
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
                <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['bBchStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tBchNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if( (isset($aDataFilter['tCstCodeFrom']) && !empty($aDataFilter['tCstCodeFrom'])) && (isset($aDataFilter['tCstCodeTo']) && !empty($aDataFilter['tCstCodeTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ลูกค้า =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom'].' : </span>'.$aDataFilter['tCstCodeFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstTo'].' : </span>'.$aDataFilter['tCstCodeTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

        </div>
    </div>
</div>