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
						<th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOTBDocNo')?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOTBDocDate')?></th>
                        <th nowrap class="xCNTextBold"><?php echo language('document/purchaseorder/purchaseorder','tPOTBStaDoc')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $nKey => $aValue): ?>
                            <?php
                                if ($tDBRDocType == 1) {
                                    $tDBRDocNo  = $aValue['FTXshDocNo'];
                                    $tDBRStaDoc  = $aValue['FTXshStaDoc'];
                                    $tDBRStaRef  = $aValue['FNXshStaRef'];
                                    $tDBRStaApv  = $aValue['FTXshStaApv'];
                                    $tDBRDocDate  = $aValue['FDXshDocDate'];
                                    $tDBRDocTime  = $aValue['FTXshDocTime'];
                                    $tDBRDocType = 1;
                                }else{
                                    $tDBRDocNo  = $aValue['FTXshDocNo'];
                                    $tDBRStaDoc  = $aValue['FTXshStaDoc'];
                                    $tDBRStaRef  = $aValue['FNXshStaRef'];
                                    $tDBRStaApv  = $aValue['FTXshStaApv'];
                                    $tDBRDocDate  = $aValue['FDXshDocDate'];
                                    $tDBRDocTime  = $aValue['FTXshDocTime'];
                                    $tDBRDocType = $tDBRDocType;
                                }
                                
                                $tDBRBchCode  = $aValue['FTBchCode'];
                                $tDBRBchName  = $aValue['FTBchName'];

                                //FTXshStaDoc
                                if ($tDBRStaDoc == 3) {
                                    $tClassStaDoc = 'text-danger';
                                    $tStaDoc = language('common/main/main', 'tStaDoc3');
                                } else if ($tDBRStaApv == 1) {
                                    $tClassStaDoc = 'text-success';
                                    $tStaDoc = language('common/main/main', 'tStaDoc1');
                                } else {
                                    $tClassStaDoc = 'text-warning';
                                    $tStaDoc = language('common/main/main', 'tStaDoc');
                                }
                                 //FTXshStaDoc
                                if($tDBRStaRef == 2){
                                    $tClassStaRef = 'text-success';
                                }else if($tDBRStaRef == 1){
                                    $tClassStaRef = 'text-warning';    
                                }else if($tDBRStaRef == 0){
                                    $tClassStaRef = 'text-danger';
                                }
                                $tClassPrcStk = 'text-success';
                                $bIsApvOrCancel = ($tDBRStaApv == 1 || $tDBRStaApv == 2) || ($tDBRStaDoc == 3 );
                                
                            ?>
                            <tr style="cursor:pointer;" class="text-center xCNTextDetail2 xWPIDocItems xPurchaseInvoiceRefInt" 
                                id="otrPurchaseInvoiceRefInt<?php echo $nKey?>" 
                                data-docno="<?php echo $tDBRDocNo?>"
                                data-docdate="<?php echo date("Y-m-d", strtotime($tDBRDocDate))?>"
                                data-doctime="<?php echo $tDBRDocTime?>"
                                data-bchcode="<?php echo $tDBRBchCode?>"
                                data-bchname="<?php echo $tDBRBchName?>" 
                                data-doctype="<?php echo $tDBRDocType?>"
                                data-cstcode="<?php echo $aValue['FTCstCode']?>"
                                data-cstname="<?php echo $aValue['FTCstName']?>"
                                data-csttel="<?php echo $aValue['FTCstTel']?>"
                                data-cstmail="<?php echo $aValue['FTCstEmail']?>"
                            >
                                <td nowrap class="text-left"><?php echo (!empty($aValue['FTBchName']))? $aValue['FTBchName']   : '-' ?></td>
                                <td nowrap class="text-left"><?php echo (!empty($tDBRDocNo))? $tDBRDocNo : '-' ?></td>
                                <td nowrap class="text-center"><?php echo (!empty($tDBRDocDate))? $tDBRDocDate: '-' ?></td>
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
            <button onclick="JSvDBRRefIntClickPageList('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
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
                <button onclick="JSvDBRRefIntClickPageList('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvDBRRefIntClickPageList('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<div id="odvDBRRefIntDocDetail"></div>

<?php include('script/jDepositBoxReservationRefDocDataTable.php')?>

