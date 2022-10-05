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
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('common/main/main','tAgency');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptCustomerGroup');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report','tRptCustomerCode');?></th> 
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:20%;"><?=language('report/report/report','tRptCustomerName');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('customer/customer/customer','tCSTBirthday');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptGender');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:5%;"><?=language('report/report/report','tRptAge');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('customer/customer/customer','tCSTContactTel');?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:10%;"><?=language('customer/customer/customer','tCSTContactEmail');?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <tr>
                                    <td class="xCNRptDetail"><?=$aValue['FTAgnName']?></td>
                                    <td class="xCNRptDetail"><?=$aValue['FTPplName']?></td>
                                    <td class="xCNRptDetail"><?=$aValue['FTCstCode']?></td>
                                    <td class="xCNRptDetail" ><?=$aValue['FTCstName']?></td>
                                    <td class="xCNRptDetail"><?=date('d/m/Y', strtotime($aValue['FDCstDob']));?></td>
                                    <td class="xCNRptDetail">
                                        <?php 
                                            if($aValue['FTCstSex'] == ''){
                                                echo 'ไม่ระบุ';
                                            }else if($aValue['FTCstSex'] == 1){
                                                echo language('report/report/report','tRptGender1');
                                            }else if($aValue['FTCstSex'] == 2){
                                                echo language('report/report/report','tRptGender2');
                                            }else{
                                                echo '-';
                                            }
                                        ?>
                                    </td>
                                    <td class="xCNRptDetail"><?=($aValue['FDCstAge'] == '') ? '-' : $aValue['FDCstAge'];?></td>
                                    <td class="xCNRptDetail"><?=($aValue['FTCstTel'] == '') ? '-' : $aValue['FTCstTel'];?></td>
                                    <td class="xCNRptDetail"><?=($aValue['FTCstEmail'] == '') ? '-' : $aValue['FTCstEmail'];?></td>
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

            <!-- ===== ฟิวเตอร์ข้อมูล เพศ =================================== -->
            <div class="xCNRptFilterBox">
                <div class="text-left xCNRptFilter">
                    <?php 
                        if($aDataFilter['tCstGender'] == ''){
                            $tGender = language('report/report/report','tRptAll');
                        }else if($aDataFilter['tCstGender'] == '1'){
                            $tGender = language('report/report/report','tRptGender1');
                        }else if($aDataFilter['tCstGender'] == '2'){
                            $tGender = language('report/report/report','tRptGender2');
                        }
                    ?>
                    <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptGender'].' : </span>'.$tGender;?></label>
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

            <?php if( (isset($aDataFilter['tCstCreateOnFrom']) && !empty($aDataFilter['tCstCreateOnFrom'])) && (isset($aDataFilter['tCstCreateOnTo']) && !empty($aDataFilter['tCstCreateOnTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล วันที่ลงทะเบียน =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCreateOnFrom'].' : </span>'.$aDataFilter['tCstCreateOnFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCreateOnTo'].' : </span>'.$aDataFilter['tCstCreateOnTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

            <?php if( (isset($aDataFilter['tCstLevelFrom']) && !empty($aDataFilter['tCstLevelFrom'])) && (isset($aDataFilter['tCstLevelTo']) && !empty($aDataFilter['tCstLevelTo']))) { ?>
                <!-- ===== ฟิวเตอร์ข้อมูล ระดับ =================================== -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptLevelCstFrom'].' : </span>'.$aDataFilter['tCstLevelNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptLevelCstTo'].' : </span>'.$aDataFilter['tCstLevelNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>

        </div>
    </div>
</div>