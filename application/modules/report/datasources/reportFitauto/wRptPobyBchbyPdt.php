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
<div id="odvRptPobyBchbyPdteHtml">
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
                            <th nowrap class="text-center   xCNRptColumnHeader" style="width:10%;"><?=$aDataTextRef['tRptPoByBchByPdtPdtCode'];?></th>
                            <th nowrap class="text-center     xCNRptColumnHeader" style="width:35%;"><?=$aDataTextRef['tRptPoByBchByPdtPdtName'];?></th>
                            <th nowrap class="text-center   xCNRptColumnHeader" style="width:10%;"><?=$aDataTextRef['tRptPoByBchByPdtDocDate'];?></th>
                            <th nowrap class="text-center   xCNRptColumnHeader" style="width:10%;"><?=$aDataTextRef['tRptPoByBchByPdtDocNo'];?></th>
                            <th nowrap class="text-center     xCNRptColumnHeader" style="width:10%;"><?=$aDataTextRef['tRptPoByBchByPdtBarCode'];?></th>
                            <th nowrap class="text-center   xCNRptColumnHeader" style="width:10%;"><?=$aDataTextRef['tRptPoByBchByPdtPunName'];?></th>
                            <th nowrap class="text-right    xCNRptColumnHeader" style="width:10%;"><?=$aDataTextRef['tRptPoByBchByPdtUnit'];?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])):?>
                            <?php
                                // Set ตัวแปร SumSub Footer
                                $nQty_SubTotal  = 0;
                                // Set ตัวแปร Sum Footer
                                $nQty_Footer    = 0;
                                // Check Branch 
                                $tChkBranch     = "";
                                $tChkProduct    = "";
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey=>$aValue): ?>
                                <?php
                                    // Step 1 เตรียม Parameter สำหรับการ Groupping 
                                    $tBchCode       = $aValue["FTBchCode"];    
                                    $tBchName       = $aValue["FTBchName"];
                                    $nGroupMember   = $aValue["FNRptGroupMember"];
                                    $nRowPartID     = $aValue["FNRowPartID"];
                                    // Groupping Head Ware House
                                    $tTextShowGroupping = $aDataTextRef['tRptPoByBchByPdtBchGrpSub'].'&nbsp('.$tBchCode.') '.$tBchName;
                                    $aGrouppingData     = array($tBchCode);
                                    if ($tChkBranch != $tBchCode) {
                                        echo '<tr style="border-top: dashed 1px #333 !important;">';
                                        for ($i = 0; $i < FCNnHSizeOf($aGrouppingData); $i++) {
                                            if ($aGrouppingData[$i] !== 'N') {
                                                echo "<td class='xCNRptGrouPing  text-left' colspan='7' style='padding: 5px;'>".$tTextShowGroupping."</td>";
                                            } else {
                                                echo "<td class='xCNRptGrouPing text-right'  style='padding: 5px;'></td>";
                                            }
                                        }
                                        echo '</tr>';
                                    }
                                ?>

                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                                    <td nowrap class="text-center   xCNRptDetail"><?php echo $aValue["FTPdtCode"];?></td>
                                    <td nowrap class="text-left     xCNRptDetail"><?php echo $aValue["FTXpdPdtName"];?></td>
                                    <td nowrap class="text-center   xCNRptDetail"><?php echo $aValue["FDXphDocDate"];?></td>
                                    <td nowrap class="text-center   xCNRptDetail"><?php echo $aValue["FTXphDocNo"];?></td>
                                    <td nowrap class="text-left     xCNRptDetail"><?php echo $aValue["FTXpdBarCode"];?></td>
                                    <td nowrap class="text-center   xCNRptDetail"><?php echo $aValue["FTPunName"];?></td>
                                    <td nowrap class="text-right    xCNRptDetail"><?php echo number_format($aValue["FCXpdQty"],1);?></td>
                                </tr>

                                <?php 
                                    //Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer
                                    $tPdtCode       = $aValue["FTPdtCode"];
                                    $tPdtName       = $aValue["FTXpdPdtName"];
                                    $nSubSumXpdQty  = number_format($aValue["FCXpdQty_SubTotal"],2);
                                    $aSumFooter     = array(
                                        $aDataTextRef['tRptPoByBchByPdtPdtGrpSub'].'&nbsp('.$tPdtCode.') '.$tPdtName,
                                        'N',
                                        'N',
                                        'N',
                                        'N',
                                        $nSubSumXpdQty
                                    );

                                    if($nRowPartID == $nGroupMember){
                                        echo '<tr style="border-bottom: dashed 1px #333 !important;">';
                                        for($i = 0;$i<FCNnHSizeOf($aSumFooter);$i++){
                                            if($aSumFooter[$i] !='N'){
                                                $tFooterVal =   $aSumFooter[$i];
                                            }else{
                                                $tFooterVal =   '';
                                            }
                                            if(intval($i) == 0){
                                                $tClassCss = "text-left";
                                            }else{
                                                $tClassCss = "text-right";
                                            }
                                            if(intval($i) == 0){
                                                $nColspan = "colspan=2";
                                            }else{
                                                $nColspan = "colspan=0";
                                            }
                                            echo "<td class='xCNRptGrouPing $tClassCss' $nColspan style='border-top: dashed 1px #333 !important;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$tFooterVal."</td>";
                                        }
                                        echo "</tr>";
                                        $nCountDataAll = FCNnHSizeOf($aDataReport['aRptData']);
                                        if($nCountDataAll - 1 != $nKey){
                                            echo "<tr><td class='xCNRptGrouPing' colspan='8' style='border-top: dashed 1px #333 !important;'></td></tr>";
                                        }
                                    }

                                    // Set Array Footer Report
                                    $nSumFooterXpdQty   = number_format($aValue["FCXpdQty_Footer"],2);
                                    $paFooterSumData    = array(
                                        $aDataTextRef['tRptPoByBchByPdtBchGrpFooter'],
                                        'N',
                                        'N',
                                        'N',
                                        'N',
                                        'N',
                                        $nSumFooterXpdQty
                                    );
                                ?>


                                <?php 
                                    $tChkBranch     = $aValue['FTBchCode']; 
                                    $tChkProduct    = $aValue['FTPdtCode'];
                                ?>
                            <?php endforeach;?>
                            <?php
                                //Step 6 : สั่ง Summary Footer
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                FCNtHRPTSumFooter($nPageNo,$nTotalPage,$paFooterSumData);
                            ?>
                        <?php else:?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo @$aDataTextRef['tRptAdjStkNoData'];?></td></tr>
                        <?php endif;?>
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
            <?php if( (isset($aDataFilter['tRptPdtCodeFrom']) && !empty($aDataFilter['tRptPdtCodeFrom'])) && (isset($aDataFilter['tRptPdtCodeTo']) && !empty($aDataFilter['tRptPdtCodeTo']))) { ?>
                <!-- ============================ ฟิวเตอร์ข้อมูล สินค้า ============================ -->
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeFrom'].' : </span>'.$aDataFilter['tRptPdtNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tPdtCodeTo'].' : </span>'.$aDataFilter['tRptPdtNameTo'];?></label>
                    </div>
                </div>
            <?php } ;?>    

        </div>
    </div>
</div>
