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
                                if ($tCRVDocType == 1) {
                                    $tCRVDocNo  = $aValue['FTXphDocNo'];
                                    $tCRVStaDoc  = $aValue['FTXphStaDoc'];
                                    $tCRVStaRef  = $aValue['FNXphStaRef'];
                                    $tCRVStaApv  = $aValue['FTXphStaApv'];
                                    $tCRVDocDate  = $aValue['FDXphDocDate'];
                                    $tCRVDocTime  = $aValue['FTXshDocTime'];
                                    $tCRVVATInOrEx  = $aValue['FTXphVATInOrEx'];
                                    $tCRVCrTerm  = $aValue['FNXphCrTerm'];
                                    $tSplCode  = $aValue['FTSplCode'];
                                    $tSplName  = $aValue['FTSplName'];
                                    $tCRVDocType = 1;
                                }else{
                                    $tCRVDocNo  = $aValue['FTXphDocNo'];
                                    $tCRVStaDoc  = $aValue['FTXphStaDoc'];
                                    $tCRVStaRef  = $aValue['FNXphStaRef'];
                                    $tCRVStaApv  = $aValue['FTXphStaApv'];
                                    $tCRVDocDate  = $aValue['FDXphDocDate'];
                                    $tCRVDocTime  = $aValue['FTXshDocTime'];
                                    $tCRVVATInOrEx  = $aValue['FTXphVATInOrEx'];
                                    $tCRVCrTerm  = $aValue['FNXphCrTerm'];;
                                    $tSplCode  = @$aDataList['raMainSpl']['raItems'][0]['FTSplCode'];
                                    $tSplName  = @$aDataList['raMainSpl']['raItems'][0]['FTSplName'];
                                    $tCRVDocType = $tCRVDocType;
                                }
                                
                                $tCRVBchCode  = $aValue['FTBchCode'];
                                $tCRVBchName  = $aValue['FTBchName'];

                                //FTXphStaDoc
                                if ($tCRVStaDoc == 3) {
                                    $tClassStaDoc = 'text-danger';
                                    $tStaDoc = language('common/main/main', 'tStaDoc3');
                                } else if ($tCRVStaApv == 1) {
                                    $tClassStaDoc = 'text-success';
                                    $tStaDoc = language('common/main/main', 'tStaDoc1');
                                } else {
                                    $tClassStaDoc = 'text-warning';
                                    $tStaDoc = language('common/main/main', 'tStaDoc');
                                }
                                 //FTXphStaDoc
                                if($tCRVStaRef == 2){
                                    $tClassStaRef = 'text-success';
                                }else if($tCRVStaRef == 1){
                                    $tClassStaRef = 'text-warning';    
                                }else if($tCRVStaRef == 0){
                                    $tClassStaRef = 'text-danger';
                                }
                                $tClassPrcStk = 'text-success';
                                $bIsApvOrCancel = ($tCRVStaApv == 1 || $tCRVStaApv == 2) || ($tCRVStaDoc == 3 );
                                
                            ?>
                            <tr style="cursor:pointer;" class="text-center xCNTextDetail2 xWPIDocItems xPurchaseInvoiceRefInt" 
                                id="otrPurchaseInvoiceRefInt<?php echo $nKey?>" 
                                data-docno="<?php echo $tCRVDocNo?>"
                                data-docdate="<?php echo date("Y-m-d", strtotime($tCRVDocDate))?>"
                                data-doctime="<?php echo $tCRVDocTime?>"
                                data-bchcode="<?php echo $tCRVBchCode?>"
                                data-bchname="<?php echo $tCRVBchName?>"
                                data-vatinroex="<?php echo $tCRVVATInOrEx?>"
                                data-crtrem="<?php echo $tCRVCrTerm?>"
                                data-splcode="<?php echo $tSplCode?>"
                                data-splname="<?php echo $tSplName?>"
                                data-doctype="<?php echo $tCRVDocType?>"
                            >
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTBchName']))? $aValue['FTBchName']   : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($aValue['BCHNameTo']))? $aValue['BCHNameTo']   : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($tCRVDocNo))? $tCRVDocNo : '-' ?></td>
                                <td nowrap class="text-center"><?php echo (!empty($tCRVDocDate))? $tCRVDocDate: '-' ?></td>
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
            <button onclick="JSvCRVRefIntClickPageList('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                <button onclick="JSvCRVRefIntClickPageList('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvCRVRefIntClickPageList('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<div id="odvCRVRefIntDocDetail"></div>

<?php include('script/jDeliveryOrderRefDocDataTable.php')?>

