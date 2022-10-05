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
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:3%;"><?=language('report/report/report','tRowNumber');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" ><?=language('report/report/report','tRptSalByPdtSetBranch');?></th> 
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:8%;"><?=language('report/report/report','tRptRPDDocNo');?></th>
                            <th nowrap class="text-center xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptDocDate');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:8%;"><?=language('report/report/report','tRptCustCode');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" ><?=language('report/report/report','tRptCustName');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:8%;"><?=language('report/report/report','tRptVat');?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:8%;"><?=language('report/report/report','tRptDepositVal');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" ><?=language('customer/customer/customer','tRptContact');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:8%;"><?=language('customer/customer/customer','tRptStatus');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <tr>
                                    <td class="xCNRptDetail"><?= $aValue['RowID']?></td>
                                    <td class="xCNRptDetail"><?= ($aValue['FTBchName']== '') ? '-' : $aValue['FTBchName'] ?></td>
                                    <td class="xCNRptDetail"><?= ($aValue['FTXshDocNo']== '') ? '-' : $aValue['FTXshDocNo']?></td>
                                    <td class="xCNRptDetail text-center"><?=date('d/m/Y', strtotime($aValue['FDXshDocDate']));?></td>
                                    <td class="xCNRptDetail"><?= ($aValue['FTCstCode']== '') ? '-' : $aValue['FTCstCode']?></td>
                                    <td class="xCNRptDetail"><?= ($aValue['FTCstName']== '') ? '-' : $aValue['FTCstName'] ?></td>
                                    <td class="xCNRptDetail text-right"><?= number_format($aValue['FCXshVat'],2)?></td>
                                    <td class="xCNRptDetail text-right"><?= number_format($aValue['FCXshGrand'],2)?></td>
                                    <td class="xCNRptDetail"><?= ($aValue['FTCtrName']== '') ? '-' : $aValue['FTCtrName'] ?></td>
                                    <?php 
                                        if($aValue['FTDpsSta'] == ''){
                                            $tTextDpsSta = language('report/report/report','tRptDepositStatus1');
                                        }else if($aValue['FTDpsSta'] == '1'){
                                            $tTextDpsSta = language('report/report/report','tRptDepositStatus1');
                                        }else if($aValue['FTDpsSta'] == '2'){
                                            $tTextDpsSta = language('report/report/report','tRptDepositStatus2');
                                        }else if($aValue['FTDpsSta'] == '3'){
                                            $tTextDpsSta = language('report/report/report','tRptDepositStatus3');
                                        }else if($aValue['FTDpsSta'] == '4'){
                                            $tTextDpsSta = language('report/report/report','tRptDepositStatus4');
                                        }
                                    ?>
                                    <td class="xCNRptDetail"><?= $tTextDpsSta?></td>
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

            <!-- ===== ฟิวเตอร์ข้อมูลสถานะ =================================== -->
            <div class="xCNRptFilterBox">
                <div class="text-left xCNRptFilter">
                    <?php 
                        if($aDataFilter['tStatusDeposit'] == ''){
                            $tTextDpsSta = language('report/report/report','tRptAll');
                        }else if($aDataFilter['tStatusDeposit'] == '1'){
                            $tTextDpsSta = language('report/report/report','tRptDepositStatus1');
                        }else if($aDataFilter['tStatusDeposit'] == '2'){
                            $tTextDpsSta = language('report/report/report','tRptDepositStatus2');
                        }else if($aDataFilter['tStatusDeposit'] == '3'){
                            $tTextDpsSta = language('report/report/report','tRptDepositStatus3');
                        }else if($aDataFilter['tStatusDeposit'] == '4'){
                            $tTextDpsSta = language('report/report/report','tRptDepositStatus4');
                        }
                    ?>
                    <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDepositStatus'].' : </span>'.$tTextDpsSta;?></label>
                </div>
            </div>
            
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