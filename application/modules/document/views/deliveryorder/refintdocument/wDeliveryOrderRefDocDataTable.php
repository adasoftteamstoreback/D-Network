<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage   = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }

?>
<div class="">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="otbSOTblDataDocHDList" class="table table-striped">
                <thead>
                    <tr class="xCNCenter">
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','สาขาที่สร้างเอกสาร')?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','สาขาปลายทาง')?></th>
						<th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOTBDocNo')?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOTBDocDate')?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOTBStaDoc')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <?php
                                if ($tDODocType == 1) {
                                    $tDODocNo  = $aValue['FTXphDocNo'];
                                    $tDOStaDoc  = $aValue['FTXphStaDoc'];
                                    $tDOStaRef  = $aValue['FNXphStaRef'];
                                    $tDOStaApv  = $aValue['FTXphStaApv'];
                                    $tDODocDate  = $aValue['FDXphDocDate'];
                                    $tDODocTime  = $aValue['FTXshDocTime'];
                                    $tDOVATInOrEx  = $aValue['FTXphVATInOrEx'];
                                    $tDOCrTerm  = $aValue['FNXphCrTerm'];
                                    $tSplCode  = $aValue['FTSplCode'];
                                    $tSplName  = $aValue['FTSplName'];
                                    $tDODocType = 1;
                                }else{
                                    $tDODocNo  = $aValue['FTXphDocNo'];
                                    $tDOStaDoc  = $aValue['FTXphStaDoc'];
                                    $tDOStaRef  = $aValue['FNXphStaRef'];
                                    $tDOStaApv  = $aValue['FTXphStaApv'];
                                    $tDODocDate  = $aValue['FDXphDocDate'];
                                    $tDODocTime  = $aValue['FTXshDocTime'];
                                    $tDOVATInOrEx  = $aValue['FTXphVATInOrEx'];
                                    $tDOCrTerm  = $aValue['FNXphCrTerm'];;
                                    $tSplCode  = @$aDataList['raMainSpl']['raItems'][0]['FTSplCode'];
                                    $tSplName  = @$aDataList['raMainSpl']['raItems'][0]['FTSplName'];
                                    $tDODocType = $tDODocType;
                                }
                                
                                $tDOBchCode  = $aValue['FTBchCode'];
                                $tDOBchName  = $aValue['FTBchName'];

                                //FTXphStaDoc
                                if ($tDOStaDoc == 3) {
                                    $tClassStaDoc = 'text-danger';
                                    $tStaDoc = language('common/main/main', 'tStaDoc3');
                                } else if ($tDOStaApv == 1) {
                                    $tClassStaDoc = 'text-success';
                                    $tStaDoc = language('common/main/main', 'tStaDoc1');
                                } else {
                                    $tClassStaDoc = 'text-warning';
                                    $tStaDoc = language('common/main/main', 'tStaDoc');
                                }
                                 //FTXphStaDoc
                                if($tDOStaRef == 2){
                                    $tClassStaRef = 'text-success';
                                }else if($tDOStaRef == 1){
                                    $tClassStaRef = 'text-warning';    
                                }else if($tDOStaRef == 0){
                                    $tClassStaRef = 'text-danger';
                                }
                                $tClassPrcStk = 'text-success';
                                $bIsApvOrCancel = ($tDOStaApv == 1 || $tDOStaApv == 2) || ($tDOStaDoc == 3 );
                                
                            ?>
                            <tr style="cursor:pointer;" class="text-center xCNTextDetail2 xWPIDocItems xPurchaseInvoiceRefInt" 
                                id="otrPurchaseInvoiceRefInt<?php echo $nKey?>" 
                                data-docno="<?php echo $tDODocNo?>"
                                data-docdate="<?php echo date("Y-m-d", strtotime($tDODocDate))?>"
                                data-doctime="<?php echo $tDODocTime?>"
                                data-bchcode="<?php echo $tDOBchCode?>"
                                data-bchname="<?php echo $tDOBchName?>"
                                data-vatinroex="<?php echo $tDOVATInOrEx?>"
                                data-crtrem="<?php echo $tDOCrTerm?>"
                                data-splcode="<?php echo $tSplCode?>"
                                data-splname="<?php echo $tSplName?>"
                                data-doctype="<?php echo $tDODocType?>"
                            >
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTBchName']))? $aValue['FTBchName']   : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['BCHNameTo']))? $aValue['BCHNameTo']   : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($tDODocNo))? $tDODocNo : '-' ?></td>
                                <td nowrap class="text-center"><?php echo (!empty($tDODocDate))? $tDODocDate: '-' ?></td>
                                <!-- <td nowrap class="text-left">
                                        <?php echo (!empty($aValue['FTSplName']))? $aValue['FTSplName'] : '-' ?>
                                </td> -->
                                <td nowrap class="text-left">
                                    <label class="xCNTDTextStatus <?php echo $tClassStaDoc;?>">
                                        <?php echo $tStaDoc ?>
                                    </label>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <p><?php echo language('common/main/main','tResultTotalRecord')?> <?php echo $aDataList['rnAllRow']?> <?php echo language('common/main/main','tRecord')?> <?php echo language('common/main/main','tCurrentPage')?> <?php echo $aDataList['rnCurrentPage']?> / <?php echo $aDataList['rnAllPage']?></p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPIPageDataTable btn-toolbar pull-right">
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvDORefIntClickPageList('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>

            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataList['rnAllPage'],$nPage+2)); $i++){?>
                <?php 
                    if($nPage == $i){ 
                        $tActive = 'active'; 
                        $tDisPageNumber = 'disabled';
                    }else{ 
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
                <button onclick="JSvDORefIntClickPageList('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvDORefIntClickPageList('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<div id="odvDORefIntDocDetail"></div>

<?php include('script/jDeliveryOrderRefDocDataTable.php')?>

