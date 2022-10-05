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
<div id="odvRptStkCountVarianceHtml">
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
                            <th nowrap class="text-center  xCNRptColumnHeader" style="width:5%;"><?=$aDataTextRef['tRptStkcountvarianceNo'];?></th>
                            <th nowrap class="text-center  xCNRptColumnHeader" style="width:15%;"><?=$aDataTextRef['tRptStkcountvarianceDocNo'];?></th>
                            <th nowrap class="text-center  xCNRptColumnHeader" style="width:15%;"><?=$aDataTextRef['tRptStkcountvariancePdtCode'];?></th>
                            <th nowrap class="text-left  xCNRptColumnHeader" style="width:25%;"><?=$aDataTextRef['tRptStkcountvariancePdtName'];?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:10%;"><?=$aDataTextRef['tRptStkcountvarianceAjdWahB4Adj'];?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:10%;"><?=$aDataTextRef['tRptStkcountvarianceAjdQtyAll'];?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:10%;"><?=language('report/report/report', 'tRptStkBeforecount')?></th>
                            <th nowrap class="text-right  xCNRptColumnHeader" style="width:10%;"><?=$aDataTextRef['tRptStkcountvarianceAjdQtyAllDiff'];?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])):?>
                            <?php
                                // Set ตัวแปร SumSubFooter
                                $nAjdWahB4Adj_SubTotal      = 0;
                                $nAjdQtyAll_SubTotal        = 0;
                                $nAjdQtyAllDiff_SubTotal    = 0;
                                // Set ตัวแปร SumFooter
                                $nAjdWahB4Adj_Footer        = 0;
                                $nAjdQtyAll_Footer          = 0;
                                $nAjdQtyAllDiff_Footer      = 0;
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey=>$aValue): ?>
                                <?php
                                    // Step 1 เตรียม Parameter สำหรับการ Groupping
                                    $tWahCode       = $aValue["FTWahCode"];    
                                    $tWahName       = $aValue["FTWahName"];
                                    $nGroupMember   = $aValue["FNRptGroupMember"];
                                    $nRowPartID     = $aValue["FNRowPartID"];
                                    // Groupping Head Ware House
                                    $tTextShowGroupping = $aDataTextRef['tRptStkcountvarianceGrpWah'].'&nbsp('.$tWahCode.') '.$tWahName;
                                    $aGrouppingData     = array($tWahCode);
                                    if ($nRowPartID == 1) {
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
                                    $nBeforeCount = $aValue["FCAjdWahB4Adj"] + ($aValue["FCAjdQtyAllDiff"]);
                                ?>
                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                                    <td nowrap class="text-center   xCNRptDetail"><?=$aValue['RowID']?></td>
                                    <td nowrap class="text-center   xCNRptDetail"><?php echo $aValue["FTAjhDocNo"];?></td>
                                    <td nowrap class="text-center   xCNRptDetail"><?php echo $aValue["FTPdtCode"];?></td>
                                    <td nowrap class="text-left     xCNRptDetail"><?php echo $aValue["FTPdtName"];?></td>
                                    <td nowrap class="text-right    xCNRptDetail"><?php echo number_format($aValue["FCAjdWahB4Adj"],$nOptDecimalShow);?></td>
                                    <td nowrap class="text-right    xCNRptDetail"><?php echo number_format($aValue["FCAjdQtyAll"],$nOptDecimalShow);?></td>
                                    <td nowrap class="text-right    xCNRptDetail"><?php echo number_format($nBeforeCount,$nOptDecimalShow);?></td>
                                    <td nowrap class="text-right    xCNRptDetail"><?php echo number_format($aValue["FCAjdQtyAllDiff"],$nOptDecimalShow);?></td>
                                </tr>
                                <?php
                                    //Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer
                                    $nSubSumAjdWahB4Adj     = number_format($aValue["FCAjdWahB4Adj_SubTotal"],$nOptDecimalShow);
                                    $nSubSumAjdQtyAll       = number_format($aValue["FCAjdQtyAll_SubTotal"],$nOptDecimalShow);
                                    $nSubSumAjdQtyAllDiff   = number_format($aValue["FCAjdQtyAllDiff_SubTotal"],$nOptDecimalShow);
                                    $nBeforeCountSubFooter =  (float) $aValue["FCAjdWahB4Adj_SubTotal"] + (float) $aValue["FCAjdQtyAllDiff_SubTotal"];

                                    $aSumFooter = array(
                                        $aDataTextRef['tRptStkcountvarianceSubGrpWah'].'&nbsp('.$tWahCode.') '.$tWahName,
                                        'N',
                                        'N',
                                        $nSubSumAjdWahB4Adj,
                                        $nSubSumAjdQtyAll,
                                        number_format($nBeforeCountSubFooter, $nOptDecimalShow),
                                        $nSubSumAjdQtyAllDiff
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
                                            echo "<td class='xCNRptGrouPing $tClassCss' $nColspan style='border-top: dashed 1px #333 !important;'>".$tFooterVal."</td>";
                                        }
                                        echo "</tr>";
                                        $nCountDataAll = FCNnHSizeOf($aDataReport['aRptData']);
                                        // if($nCountDataAll - 1 != $nKey){
                                        //     echo "<tr><td class='xCNRptGrouPing' colspan='8' style='border-top: dashed 1px #333 !important;'></td></tr>";
                                        // }
                                    }

                                    // Set Array Footer Report
                                    $nSumFooterAjdWahB4Adj      = number_format($aValue["FCAjdWahB4Adj_Footer"],$nOptDecimalShow);
                                    $nSumFooterAjdQtyAll        = number_format($aValue["FCAjdQtyAll_Footer"],$nOptDecimalShow);
                                    $nSumFooterAjdQtyAllDiff    = number_format($aValue["FCAjdQtyAllDiff_Footer"],$nOptDecimalShow);
                                    $nBeforeCountFooter         = (float) $aValue["FCAjdWahB4Adj_Footer"] + (float) $aValue["FCAjdQtyAllDiff_Footer"];

                                    $paFooterSumData            = array(
                                        $aDataTextRef['tRptStkcountvarianceSumFooter'],
                                        'N',
                                        'N',
                                        'N',
                                        $nSumFooterAjdWahB4Adj,
                                        $nSumFooterAjdQtyAll,
                                        number_format($nBeforeCountFooter, $nOptDecimalShow),
                                        $nSumFooterAjdQtyAllDiff
                                    );
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
        </div>
    </div>
</div>

